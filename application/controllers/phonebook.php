<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class phonebook extends CI_Controller
{

    public $data = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $this->load->view('userlist');

    }

    public function viewuc($uid){

       $this->load->view('usercontact',array('uid'=>$uid));
    }

    public function userAjax()
    {
        $this->load->library('Datatables');
        $columns = array(
            'uid',
            'uid as id',
            "'slno'",
            'first_name',
            'last_name',
            'mobile_no',
            'email_id'
        );
        $columnsmap = array(
            'checkbox',
            'textbox',
            'slno',
            'textbox',
            'textbox',
            'textbox',
            'textbox'
        );

        $joins = " ";

        $where = "and sts='1'";

        $search = '';
        $groupby = '';

        echo $this->datatables->generate('tbl_users', $columns, 'tbl_users.uid', $joins, $where, $search, $groupby, $columnsmap);
    }
    public function userContactAjax($uid)
    {
        $this->load->library('Datatables');
        $columns = array(
            'id',
            'id as eid',
            "'slno'",
            'first_name',
            'last_name',
            'mobile_no',
            'email_id'
        );
        $columnsmap = array(
            'checkbox',
            'textbox',
            'slno',
            'textbox',
            'textbox',
            'textbox',
            'textbox'
        );

        $joins = " ";

        $where = "and uid='$uid'";

        $search = '';
        $groupby = '';

        echo $this->datatables->generate('tbl_user_contacts', $columns, 'tbl_user_contacts.id', $joins, $where, $search, $groupby, $columnsmap);
    }
    public function formatPostData()
    {
        $ArrPost = explode('&', $this->input->post('form'));

        foreach ($ArrPost as $value) {
            $ArrData = explode('=', $value);
            if (substr($ArrData[0], 0, 6) == 'search')
                $this->data['search'][$ArrData[0]] = urldecode($ArrData[1]);
            else
                $this->data[$ArrData[0]] = urldecode($ArrData[1]);
        }
        unset($this->data['search']);
    }

    public function addUser()
    {
        $this->formatPostData();
        $this->load->helper('email');
		if ($this->data ['firstName'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Firstname', "field" => 'firstName' ) );
			die ();
		
		} else if ($this->data ['lastName'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Lastname', "field" => 'firstName' ) );
			die ();
		
		}
		else if ($this->data ['mobileNo'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Contact Number', "field" => 'mobileNo' ) );
			die ();
		
		}
		else if ($this->data ['email'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter an email id', "field" => 'email' ) );
			die ();
		
		}
		else if(!valid_email($this->data ['email'])){
			
			echo json_encode ( array ("response" => 0, "msg" => 'Not valid email id', "field" => 'email' ) );
			die ();
		}
		else{
        $user = new Entity\User($this->data);
        $this->doctrine->em->persist($user);
        $this->doctrine->em->flush();
        echo json_encode(array(
            "response" => 1,
            "msg" => 'New User Added!!!',
            "field" => 'comments'
        ));
		
		}
    }

    public function editUser()
    {
        $this->formatPostData();
        $uid = $this->input->post('uid');

  $this->load->helper('email');
		if ($this->data ['firstName'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Firstname', "field" => 'firstName' ) );
			die ();
		
		} else if ($this->data ['lastName'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Lastname', "field" => 'firstName' ) );
			die ();
		
		}
		else if ($this->data ['mobileNo'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Contact Number', "field" => 'mobileNo' ) );
			die ();
		
		}
		else if ($this->data ['email'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter an email id', "field" => 'email' ) );
			die ();
		
		}
		else if(!valid_email($this->data ['email'])){
			
			echo json_encode ( array ("response" => 0, "msg" => 'Not valid email id', "field" => 'email' ) );
			die ();
		}
        $query = $this->doctrine->em->createQuery("SELECT u FROM Entity\\User u WHERE u.uid='$uid'");
        $user = $query->getResult();
        foreach ($this->data as $key => $value) {
            $method = 'set' . ucfirst($key);
            $user[0]->$method($value);
        }
        $this->doctrine->em->flush();
        echo json_encode(array(
            "response" => 1,
            "msg" => 'Successfully Saved',
            "field" => 'email'
        ));
        die();
    }

    public function getUser()
    {
        $uid = $this->input->post('uid');
        $query = $this->doctrine->em->createQuery("SELECT u FROM Entity\\User u WHERE u.uid='$uid'");
        $user = $query->getResult();



		foreach ( get_class_methods ( $user [0] ) as $value ) {

			if (substr ( $value, 0, 3 ) == 'get') {

					$method = $value;
					$result [lcfirst ( substr ( $value, 3 ) )] = $user [0]->$method ();

			}
		}

		echo json_encode ( $result );
    }

    public function deleteUser()
    {
        $ids = explode(',', $this->input->post('ids'));
        foreach ($ids as $id) {
            // $uid=3;
            $query = $this->doctrine->em->createQuery("SELECT u FROM Entity\\User u WHERE u.uid='$id'");
            $user = $query->getResult();
            $this->doctrine->em->remove($user[0]);
            $this->doctrine->em->flush();
        }
        echo json_encode(array(
            "response" => 1,
            "msg" => 'removed Successfully ',
            "field" => 'email'
        ));
        die();
    }

    public function addUserContact()
    {
        $this->formatPostData();
		  $this->load->helper('email');
		  if ($this->data ['uid'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Select the Username', "field" => 'uid' ) );
			die ();
		
		}
		else if ($this->data ['firstName'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Firstname', "field" => 'firstName' ) );
			die ();
		
		} else if ($this->data ['lastName'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Lastname', "field" => 'firstName' ) );
			die ();
		
		}
		else if ($this->data ['mobileNo'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Contact Number', "field" => 'mobileNo' ) );
			die ();
		
		}
		else if ($this->data ['email'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter an email id', "field" => 'email' ) );
			die ();
		
		}
		else if(!valid_email($this->data ['email'])){
			
			echo json_encode ( array ("response" => 0, "msg" => 'Not valid email id', "field" => 'email' ) );
			die ();
		}
        $uid = $this->doctrine->em->getRepository ( 'Entity\User' )->findOneByUid ( $this->data ['uid'] );
        // $this->data = array('firstName' => 'test','lastName' => 'user');
        $this->data['uid']=$uid;
        $usercontact = new Entity\UserContact($this->data);
        $this->doctrine->em->persist($usercontact);
        $this->doctrine->em->flush();
        echo json_encode(array(
            "response" => 1,
            "msg" => 'New User Contact Added',
            "field" => 'comments'
        ));
    }

    public function editUserContact()
    {
        $this->formatPostData();
		  $this->load->helper('email');
		if ($this->data ['firstName'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Firstname', "field" => 'firstName' ) );
			die ();
		
		} else if ($this->data ['lastName'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Lastname', "field" => 'firstName' ) );
			die ();
		
		}
		else if ($this->data ['mobileNo'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter Contact Number', "field" => 'mobileNo' ) );
			die ();
		
		}
		else if ($this->data ['email'] == '') {
			
			echo json_encode ( array ("response" => 0, "msg" => 'Enter an email id', "field" => 'email' ) );
			die ();
		
		}
		else if(!valid_email($this->data ['email'])){
			
			echo json_encode ( array ("response" => 0, "msg" => 'Not valid email id', "field" => 'email' ) );
			die ();
		}
        $id = $this->input->post('uid');
        // $this->data = array('email' => 'test@gmail.com','mobileNo' => '12345');

        // $uid=2;
        $query = $this->doctrine->em->createQuery("SELECT u FROM Entity\\UserContact u WHERE u.id='$id'");
        $usercontact = $query->getResult();
        foreach ($this->data as $key => $value) {
            $method = 'set' . ucfirst($key);
            $usercontact[0]->$method($value);
        }
        $this->doctrine->em->flush();
        echo json_encode(array(
            "response" => 1,
            "msg" => 'Successfully Saved',
            "field" => 'email'
        ));
        die();
    }
    public function getUserContact()
    {
        $uid = $this->input->post('id');
        $query = $this->doctrine->em->createQuery("SELECT u FROM Entity\\UserContact u WHERE u.id='$uid'");
        $user = $query->getResult();



        foreach ( get_class_methods ( $user [0] ) as $value ) {

            if (substr ( $value, 0, 3 ) == 'get') {

                $method = $value;
                $result [lcfirst ( substr ( $value, 3 ) )] = $user [0]->$method ();

            }
        }

        echo json_encode ( $result );
    }

    public function deleteUserContact()
    {
        $ids = explode(',', $this->input->post('ids'));
        foreach ($ids as $id) {
        // $uid=3;
        $query = $this->doctrine->em->createQuery("SELECT u FROM Entity\\UserContact u WHERE u.id='$id'");
        $userContact = $query->getResult();
        $this->doctrine->em->remove($userContact[0]);
        $this->doctrine->em->flush();
        }
        echo json_encode(array(
            "response" => 1,
            "msg" => 'removed Successfully ',
            "field" => 'email'
        ));
        die();
    }
}
?>