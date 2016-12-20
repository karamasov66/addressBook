<?php

namespace XM\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use XM\AddressBookBundle\Entity\Contact;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="XM\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
        $this->contacts = new ArrayCollection();
    }

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="XM\AddressBookBundle\Entity\Contact", cascade={"persist"})
     */
    private $contacts;

    public function addContact(Contact $contact)
    {
        $this->contacts[] = $contact;
    }

    public function removeContact(Contact $contact)
    {
        $this->contacts->removeElement($contact);
    }
    
    public function getContacts()
    {
        return $this->contacts;
    }

    public function getId()
    {
        return $this->id;
    }
}

