<?php
namespace Heiner\Heiner\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

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
 * The repository for Persons
 */
class PersonRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public $personRepository;

    /**
     * @param \Heiner\Heiner\Domain\Repository\PersonRepository $companyRepository
     */
    public function injectPersonRepository(
        \Heiner\Heiner\Domain\Repository\PersonRepository $personRepository
    ) {
        $this->personRepository = $personRepository;
    }

    /**
     * @param $firmaid
     */
    public function getPersonsCompany($firmaid)
    {
        $queryBuilder = GeneralUtility::makeInstance(
            ConnectionPool::class
        )->getQueryBuilderForTable('tx_heiner_domain_model_person');
        $result = $queryBuilder
            ->select(
                'p.anrede',
                'p.vorname',
                'p.nachname',
                'c.name',
                'c.unterzeile',
                'c.strasse',
                'c.plz',
                'c.ort',
                'c.telefon',
                'c.fax',
                'c.web'
            )
            ->from('tx_heiner_domain_model_person', 'p')
            ->join(
                'p',
                'tx_heiner_domain_model_company',
                'c',
                $queryBuilder
                    ->expr()
                    ->eq('p.firma', $queryBuilder->quoteIdentifier('c.uid'))
            )
            ->where(
                $queryBuilder
                    ->expr()
                    ->eq(
                        'c.uid',
                        $queryBuilder->createNamedParameter(
                            $firmaid,
                            \PDO::PARAM_INT
                        )
                    )
            )
            ->execute()
            ->fetchAll();

        return $result;
    }

    public function findAllPersonsBelongingToCompany($firmaId)
    {
        // $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_heiner_domain_model_person');
        // $persons=$queryBuilder->select('anrede', 'vorname', 'nachname', 'email', 'telefon', 'handy', 'firma')
        // ->from('tx_heiner_domain_model_person')
        // ->where($queryBuilder->expr()->eq('tx_heiner_domain_model_person.firma',$queryBuilder->createNamedParameter($firmaId, \PDO::PARAM_INT)))->execute()->fetchAll();

        //  return $persons;

        $query = $this->personRepository->createQuery();
        $query->matching($query->equals('firma.uid', $firmaId));
        $persons = $query->execute();
        return $persons;
    }

    public function pagination(int $currentPage, $limit)
    {
        $total = $this->createQuery()->count('uid');
        $data['pages'] = [];
        $linksShown = (int) 3;

        $totalPages = (int) ceil($total / $limit);
        $total = (int) $total;
        for (
            $x = $currentPage - $linksShown;
            $x <= $currentPage + $linksShown;
            $x++
        ) {
            if ($x > 0 && $x <= $totalPages) {
                array_push($data['pages'], $x);
            }
        }

        $offset = (int) ($currentPage - 1) * $limit;
        $persons = $this->createQuery()
            ->setOffset($offset)
            ->setLimit($limit)
            ->execute();

        $data['persons'] = $persons;
        $data['totalPages'] = $totalPages;
        return $data;
    }
    /**
     * action delete
     *
     * @var \Heiner\Heiner\Domain\Model\Person $person
     * @return void
     */
    public function deleteMultipleEntries($personsToDelete)
    {
        foreach ($personsToDelete as $person) {
            $personToDelete = $this->findByUid($person);
            $this->remove($personToDelete);
        }

        return true;
    }
}
