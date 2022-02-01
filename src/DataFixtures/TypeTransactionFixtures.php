<?php

namespace App\DataFixtures;

use App\Entity\TypeTransaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeTransactionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $transactionTypes = array('payment', 'salary');

        foreach ($transactionTypes as $transactionType) {
            $type = new TypeTransaction();
            $type->setName($transactionType);
            $manager->persist($type);
        }
        $manager->flush();
    }
}
