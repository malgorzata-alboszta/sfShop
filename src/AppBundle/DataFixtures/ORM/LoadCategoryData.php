<?php

namespace AppBundle\DataFixtures\ORM; 

use Doctrine\Common\DataFixtures\AbstractFixture; 
use Doctrine\Common\DataFixtures\OrderedFixtureInterface; //to jest interface
use Doctrine\Common\Persistence\ObjectManager; 
use AppBundle\Entity\Category; 

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface 
{//Fixtures sa to testowe dane lub dane inicjujace np.user:administrator, zeby nierobic insert query w sql
    public function getOrder() 
    { //wymuszone przez interface OrderedFixuredInterface
        return 1; 
//kolejnosc ładowania fixtures. Kategorie chcemy miec załadowane przed produktami, ponieważ produkty odwołują się do Kategorii
    } 
    
    public function load(ObjectManager $manager) 
 //jest wywoływane według ustalonej przez getOrder() kolejności w komendzie doctrine:fixtures:load w konsoli
    { 
        $category1 = new Category(); //tworze nowy obiekt encji Category i przyppisuje to do zmiennej $category1
        $category1->setName('Dyski'); 
//category1 zapisuje na potrzebe fixtures aby móc się w innej klasie fixtures odwołac do tego obiektu 
        $this->addReference('category1', $category1);
//manager objektów musi wiedziec ze zmiany w $category1 maja zostac utrwalone (persist) w DB gdy nastapi flush
        $manager->persist($category1); 

        $category2 = new Category(); 
        $category2->setName('Akcesoria'); 
        $this->addReference('category2', $category2); 
        $manager->persist($category2); 

        $category3 = new Category(); 
        $category3->setName('Peryferia'); 
        $this->addReference('category3', $category3); 
        $manager->persist($category3); 

	$manager->flush(); //zapisanie w DB
    } 
}
