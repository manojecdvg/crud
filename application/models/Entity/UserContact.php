<?php

namespace Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User Group Model
 *
 * @Entity
 * @Table(name="tbl_user_contacts")
 * @author  Manoj Kumar P <manojecdvg@gmail.com>
 */
class UserContact
{

    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(name="first_name",type="string", length=50, nullable=false)
     */
    private $firstName;

    /**
     * @Column(name="last_name",type="string", length=50, nullable=false)
     */
    private $lastName;

    /**
     * @Column(name="email_id",type="string", length=50, unique=true, nullable=false)
     */
    private $email;

    /**
     * @Column(name="mobile_no",type="string", length=50)
     */
    private $mobileNo;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="uid", referencedColumnName="uid")
     */
    protected $uid;

    /**
     * Constructor
     */
    public function __construct($data) {


        foreach ( $data as $key => $value ) {
            $method = 'set' . ucfirst ( $key );
            $this->$method ( $value );
        }
    }


	/**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

	/**
     * @return the $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

	/**
     * @return the $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

	/**
     * @return the $email
     */
    public function getEmail()
    {
        return $this->email;
    }

	/**
     * @return the $mobileNo
     */
    public function getMobileNo()
    {
        return $this->mobileNo;
    }

	/**
     * @return the $uid
     */
    public function getUid()
    {
        return $this->uid;
    }

	/**
     * @param field_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

	/**
     * @param field_type $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

	/**
     * @param field_type $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

	/**
     * @param field_type $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

	/**
     * @param field_type $mobileNo
     */
    public function setMobileNo($mobileNo)
    {
        $this->mobileNo = $mobileNo;
    }

	/**
     * @param field_type $uid
     */
    public function setUid(User $uid)
    {
        $this->uid = $uid;
    }



}
