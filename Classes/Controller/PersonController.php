<?php
namespace Heiner\Heiner\Controller;

/***
 *
 * This file is part of the "personenundfirmen" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Heiner Giehl <heiner.giehl@tu-dortmund.de>, HeinerGiehl
 *
 ***/
/**
 * PersonController
 */
class PersonController extends
    \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * personRepository
     *
     * @var \Heiner\Heiner\Domain\Repository\PersonRepository
     */
    protected $personRepository = null;

    /**
     * companyRepository
     *
     * @var \Heiner\Heiner\Domain\Repository\CompanyRepository
     */
    protected $companyRepository = null;

    /**
     * @param \Heiner\Heiner\Domain\Repository\PersonRepository $personRepository
     */
    public function injectPersonRepository(
        \Heiner\Heiner\Domain\Repository\PersonRepository $personRepository
    ) {
        $this->personRepository = $personRepository;
    }

    /**
     * @param \Heiner\Heiner\Domain\Repository\CompanyRepository $companyRepository
     */
    public function injectCompanyRepository(
        \Heiner\Heiner\Domain\Repository\CompanyRepository $companyRepository
    ) {
        $this->companyRepository = $companyRepository;
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $currentPage = $this->request->getArguments()['pageNumber'];
      
        if (empty($currentPage)) {
            $currentPage = 1;
        }

        $currentPage = (int) $currentPage;

        // $limit = (int) $this->settings['limit'];


        $data = $this->personRepository->pagination($currentPage, $limit);
        $data['pageLimit'] = [2, 4,5, 6, 8, 10];
        $data['currentPage'] = $currentPage;
        $data['nextPage'] = $currentPage + 1;
        $data['previousPage'] = $currentPage - 1;

        $loggedInUser = $GLOBALS['TSFE']->fe_user->user;

        $data['loggedInUser'] = $loggedInUser;
   $data['defaultLimit']=$this->settings['limit'];
        $this->view->assign('data', $data);
    }

    public function ajaxListAction()
    {
     
        $ajaxPageLimit = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP(
            'ajaxPageLimit'
        );
   
        $currentPage = $this->request->getArguments()['pageNumber'];

        if (empty($currentPage)) {
            $currentPage = 1;
        }

        $currentPage = (int) $currentPage;
        $ajaxPageLimit = (int) $ajaxPageLimit;

        $data = $this->personRepository->pagination(
            $currentPage,
            $ajaxPageLimit
        );
        $this->settings['ajaxPageLimit']=$ajaxPageLimit;
         $data['pageLimit'] = [2, 4, 6, 8, 10];
        $data['currentPage'] = $currentPage;
        $data['nextPage'] = $currentPage + 1;
        $data['previousPage'] = $currentPage - 1;

        $loggedInUser = $GLOBALS['TSFE']->fe_user->user;

        $data['loggedInUser'] = $loggedInUser;
     $data['defaultLimit']=$this->settings['limit'];
        $this->view->assign('data', $data);
       
    }
    /**
     * action show
     *
     * @param \Heiner\Heiner\Domain\Model\Person $person
     * @return void
     */
    public function showAction(\Heiner\Heiner\Domain\Model\Person $person)
    {
        $persons = $this->personRepository->findAllPersonsBelongingToCompany(
            $person->getFirma()->getUid()
        );

        $this->view->assign('persons', $persons);
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
        $companies = $this->companyRepository->findAll();

        // $companyNames=[];
        // foreach($companies AS $company){
        //     $companyNames[]=$company->getName();
        // }
        $this->view->assign('companies', $companies);
    }

    /**
     *
     * action create
     *
     * @param \Heiner\Heiner\Domain\Model\Person $newPerson
     * @return void
     */
    public function createAction(\Heiner\Heiner\Domain\Model\Person $newPerson)
    {
        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($newPerson);

        // $firmaId = $newPerson->getFirmenname();
        // var_dump($newPerson->getFirma());
        // exit();
        // $firmaId = (int) $firmaId;
        // $newPerson->firma()->setUid($firmaId);
        // $company = $this->companyRepository->findByUid($newPerson->getFirma());
        // $newPerson->setFirma($company->getName());
        $this->addFlashMessage(
            'The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html',
            '',
            \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
        );
        $this->personRepository->add($newPerson);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Heiner\Heiner\Domain\Model\Person $person
     * @ignorevalidation $person
     * @return void
     */
    public function editAction(\Heiner\Heiner\Domain\Model\Person $person)
    {
        // $person = $this->personRepository->findByUid($person->getUid());
        $allCompanies = $this->companyRepository->findall();
        // $allCompanies=[];
        // foreach($allPersons as $person){
        //     array_push($allCompanies, $person->getFirma());
        // }

        $data['allCompanies'] = $allCompanies;
        $data['person'] = $person;
        $this->view->assign('data', $data);
    }

    /**
     * action update
     *
     * @param \Heiner\Heiner\Domain\Model\Person $person
     * @return void
     */
    public function updateAction(\Heiner\Heiner\Domain\Model\Person $person)
    {
        $this->personRepository->update($person);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Heiner\Heiner\Domain\Model\Person $person
     * @return void
     */
    public function deleteAction(\Heiner\Heiner\Domain\Model\Person $person)
    {
        $this->personRepository->remove($person);
        $this->redirect('list');
    }

    public function deleteMultipleEntriesAction()
    {
        $personsToDelete = array_values(
            $this->request->getArguments()['personsToDelete']
        );

        // $personsToDelete = implode(',', $personsToDelete);

        $result = $this->personRepository->deleteMultipleEntries(
            $personsToDelete
        );

        $this->redirect('list');
    }
}
