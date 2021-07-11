<?php
namespace Heiner\Heiner\Domain\Repository;


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
 * The repository for Companies
 */
class CompanyRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{


        /**

     * 
     * @var \Heiner\Heiner\Domain\Model\Person $person
     * @return void
     */
public function deleteMultipleEntries($companiesToDelete)
{
    foreach($companiesToDelete as $company){
        $companyToDelete=$this->findByUid($company);
        $this->remove( $companyToDelete); 
    }
}

public function pagination(int $currentPage, int $limit)
    {
        $total = $this->createQuery()->count('uid');
        $data['pages'] = [];
        $linksShown = (int)3;
     
        $totalPages = (int) ceil($total / $limit);
        $total = (int) $total;
        for (
            $x = $currentPage - $linksShown;
            $x < $currentPage + $linksShown + 1;
            $x++
        ) {
            if ($x > 0 && $x <= $totalPages) {
                array_push($data['pages'], $x);
            }
        }
        $offset = (int) ($currentPage - 1) * $limit;
        $companies = $this->createQuery()
            ->setOffset($offset)
            ->setLimit($limit)
            ->execute();
        $data['totalPages']=$totalPages;
        $data['companies'] = $companies;
 
        return $data;
    }
}