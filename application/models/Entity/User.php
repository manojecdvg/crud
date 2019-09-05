<?php

namespace Entity;

/**
 * User Model
 *
 * @Entity
 * @Table(name="tbl_users")
 *
 * @author Manoj Kumar P <manojecdvg@gmail.com>
 */
class User {

	/**
	 * @Id
	 * @Column(name="uid",type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $uid;

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
	 * @Column(name="sts",type="integer", length=1)
	 */
	private $sts;
	/**
	 * Constructor
	 */
	public function __construct($data) {

		foreach ( $data as $key => $value ) {
			$method = 'set' . ucfirst ( $key );

			$this->$method ( $value );
		}
		$this->setSts("1");
		//$this->setUserRoleId(2);
	}


	/**
     * @return the $uid
     */
    public function getUid()
    {
        return $this->uid;
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
     * @return the $sts
     */
    public function getSts()
    {
        return $this->sts;
    }

	/**
     * @param field_type $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
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
     * @param field_type $sts
     */
    public function setSts($sts)
    {
        $this->sts = $sts;
    }




}
