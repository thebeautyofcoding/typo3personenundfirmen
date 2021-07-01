<?php
namespace Heiner\Heiner\Controller;


/***
 *
 * This file is part of the "companyenundfirmen" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Heiner Giehl <heiner.giehl@tu-dortmund.de>, HeinerGiehl
 *
 ***/
/**
 * CompanyController
 */
class CompanyController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * companyRepository
     * 
     * @var \Heiner\Heiner\Domain\Repository\CompanyRepository
     */
    protected $companyRepository = null;

    /**
     * @param \Heiner\Heiner\Domain\Repository\CompanyRepository $companyRepository
     */
    public function injectCompanyRepository(\Heiner\Heiner\Domain\Repository\CompanyRepository $companyRepository)
    {
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
        
        $data = $this->companyRepository->pagination($currentPage);
        
        $data['currentPage'] = $currentPage;
        $data['nextPage']=$currentPage+1;
        $data['previousPage']=$currentPage-1;
 
        
        $loggedInUser= $GLOBALS['TSFE']->fe_user->user;

     
        $data['loggedInUser']=$loggedInUser;
     
 
        $this->view->assign('data', $data);
    }

    /**
     * action show
     * 
     * @param \Heiner\Heiner\Domain\Model\Company $company
     * @return void
     */
    public function showAction(\Heiner\Heiner\Domain\Model\Company $company)
    {
        $this->view->assign('company', $company);
    }

    /**
     * action new
     * 
     * @return void
     */
    public function newAction()
    {
    }

    /**
     * action create
     * 
     * @param \Heiner\Heiner\Domain\Model\Company $newCompany
     * @return void
     */
    public function createAction(\Heiner\Heiner\Domain\Model\Company $newCompany)
    {
        // $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->companyRepository->add($newCompany);
        $this->redirect('list');
    }

    /**
     * action edit
     * 
     * @param \Heiner\Heiner\Domain\Model\Company $company
     * @ignorevalidation $company
     * @return void
     */
    public function editAction(\Heiner\Heiner\Domain\Model\Company $company)
    {
        $this->view->assign('company', $company);
    }

    /**
     * action update
     * 
     * @param \Heiner\Heiner\Domain\Model\Company $company
     * @return void
     */
    public function updateAction(\Heiner\Heiner\Domain\Model\Company $company)
    {
        // $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->companyRepository->update($company);
        $this->redirect('list');
    }

    /**
     * action delete
     * 
     * @param \Heiner\Heiner\Domain\Model\Company $company
     * @return void
     */
    public function deleteAction(\Heiner\Heiner\Domain\Model\Company $company)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->companyRepository->remove($company);
        $this->redirect('list');
    }
    public function deleteMultipleEntriesAction()
    {
      
        $companiesToDelete = array_values(
            $this->request->getArguments()['companiesToDelete']
        );
      
     
        // $personsToDelete = implode(',', $personsToDelete);
 
        $result = $this->companyRepository->deleteMultipleEntries(
            $companiesToDelete
        );
        

        $this->redirect('list');
    }
    
}
