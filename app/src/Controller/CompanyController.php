<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Employee;
use App\Form\CompanyType;
use App\Form\StaffType;
use App\Repository\CompanyRepository;
use App\Repository\EmployeeRepository;
use App\Traits\UserTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company')]
class CompanyController extends AbstractController
{
    use UserTrait;

    #[Route('/', name: 'company_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository): Response
    {
        return $this->render('company/index.html.twig', [
            'companies' => $companyRepository->findAll(),
        ]);
    }

    #[Route('/ajax/registration', name: 'company_ajax_registration_resume', methods: ['GET'])]
    public function companyRegistrationResume(Request $request, CompanyRepository $companyRepository): Response
    {
        $session = $request->getSession();
        $company = $companyRepository->find($session->get('current_company')->getId());
        $staffList = $company->getStaff();
        $company = [
            "id"   => $company->getId(),
            "name" => $company->getName(),
            "type" => "COMPANY",
           // "fee"  => Company::getSubscriptionFee($company->getCategory()), // Get fee based on category if needed
        ];
//        $total = $company['fee'];
//        $employeeTotal = 0;
        $employees = [];
        foreach ($staffList as $staff){
            $employees[] = [
                "name" => $staff->getLastname() . ' ' . $staff->getFirstname(),
                "type" => "STAFF",
             //   "fee"  =>  Staff::getSubscriptionFee(), // Get fee based on category if needed
            ];
           // $employeeTotal += $employees["fee"];
        }
//        $total += $employeeTotal;
        return $this->render('company/registration_resume_ajax.html.twig',[
            "company" => $company,
            "employees" => $employees,
           // "employeeTotal" => $employeeTotal,
           // "total" => $total
        ]);
    }

    #[Route('/new', name: 'company_new', methods: ['GET', 'POST'])]
    public function new(Request            $request,
                        CompanyRepository  $companyRepository,
                        EmployeeRepository $staffRepository): Response
    {

        $session = $request->getSession();

        $company = new Company();
        $companyForm = $this->createForm(CompanyType::class, $company);
        $companyForm->handleRequest($request);

        $staff = new Employee();
        $staffForm = $this->createForm(StaffType::class, $staff);
        $staffForm->handleRequest($request);

        if ($companyForm->isSubmitted() && $companyForm->isValid()) {
            $company->setOwner($this->getUser());
            $company->setStatus("WAITING_FOR_PAYMENT");
            $companyRepository->add($company, true);
            $session->set('current_company', $company);
            return $this->json($company->getId(), Response::HTTP_CREATED);
        }

        if ($staffForm->isSubmitted()) {
            $company = $companyRepository->find( $session->get('current_company')->getId());
            $staff->setCompany($company);
            $staff->setStatus("WAITING_FOR_PAYMENT");
            $staffRepository->add($staff, true);

            $staffList = $staffRepository->findBy(["company" => $company , "status" => "WAITING_FOR_PAYMENT"]);

            return $this->render('company/staff_list_ajax.html.twig',["staffList" => $staffList]);
        }

        return $this->renderForm('company/new.html.twig', [
            'company' => $company,
            'staffForm' => $staffForm,
            'companyForm' => $companyForm,
        ]);
    }

    #[Route('/{id}', name: 'company_show', methods: ['GET'])]
    public function show(Company $company): Response
    {
        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/{id}/edit', name: 'company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRepository->add($company, true);

            return $this->redirectToRoute('company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/edit.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $companyRepository->remove($company, true);
        }

        return $this->redirectToRoute('company_index', [], Response::HTTP_SEE_OTHER);
    }
}
