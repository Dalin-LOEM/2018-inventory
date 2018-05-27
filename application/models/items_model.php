<?php
/**
 * This model contains the business logic and manages the persistence of users and roles
*/
if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This model contains the business logic and manages the persistence of users and roles
 * It is also used by the session controller for the authentication.
 */
class items_model extends CI_Model {

    /**
     * Get the list of users or one user
     * @param int $id optional id of one user
     * @return array record of users
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getitems($id = 0) {
        $this->db->select('item.iditem,item.code, item.item, item.itemdescription AS "description",condition as "condition",  item.itemcost AS "cost", item.date AS "date", category.category AS ,"cat",location.location as "locat", users.firstname AS "nameuser",department.department as "depart",material.material as "mat",model.model as "model", owner.owner as "owner"
            ');
        $this->db->join('category', 'category.idcategory = item.categoryid','left');    
        $this->db->join('material', 'material.idmaterial = item.materialid','left');    
        $this->db->join('department', 'department.iddepartment = item.departmentid','left');    
        $this->db->join('location', 'location.idlocation = item.locationid','left');    
        $this->db->join('users', 'users.id = item.userid','left');    
        $this->db->join('owner', 'owner.idowner = item.ownerid','left'); 
        $this->db->join('model', 'model.idmodel = item.modelid','left'); 
        $this->db->join('brand', 'model.brandid = brand.idbrand','left'); 
        
        if ($id === 0) {
            $query = $this->db->get('item');
            return $query->result_array();
        }
        $query = $this->db->get_where('item', array('item.iditem' => $id));
        return $query->row_array();
    }
    
    public function showEditItems($id){
        $this->db->select('item.iditem, item.item, item.itemdescription AS "description", category.category AS "cat",category.idcategory AS "catid", condition as "condition", material.material as "mat",material.idmaterial as "matid", department.department as "depat" ,department.iddepartment as "depatid" ,location.location as "locat",location.idlocation as "locatid", users.firstname AS "nameuser",users.id AS "userid", owner.owner as "owner", owner.idowner as "ownerid" , model.model as "model", model.idmodel as "modelid" , brand.brand as "brand", brand.idbrand as "brandid" , item.itemcost AS "cost", item.date AS "date"');
        $this->db->join('category', 'category.idcategory = item.categoryid','left');    
        $this->db->join('material', 'material.idmaterial = item.materialid','left');    
        $this->db->join('department', 'department.iddepartment = item.departmentid','left');    
        $this->db->join('location', 'location.idlocation = item.locationid','left');    
        $this->db->join('users', 'users.id = item.userid','left');    
        $this->db->join('owner', 'owner.idowner = item.ownerid','left'); 
        $this->db->join('model', 'model.idmodel = item.modelid','left'); 
        $this->db->join('brand', 'model.brandid = brand.idbrand','left'); 
        $this->db->where('item.iditem', $id);
        $query = $this->db->get('item');

        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    // detail item

     public function showDetailItem($id){
        $this->db->select('item.iditem, item.item, item.itemdescription AS "description", category.category AS "cat",category.idcategory AS "catid", condition as "condition", material.material as "mat",material.idmaterial as "matid", department.department as "depat" ,department.iddepartment as "depatid" ,location.location as "locat",location.idlocation as "locatid", CONCAT(skeleton_users.firstname," ",skeleton_users.lastname) AS "nameuser",users.id AS "userid", owner.owner as "owner", owner.idowner as "ownerid" , model.model as "model", model.idmodel as "modelid" , brand.brand as "brand", brand.idbrand as "brandid" , item.itemcost AS "cost", item.date AS "date", item.code, status');
        $this->db->join('category', 'category.idcategory = item.categoryid','left');    
        $this->db->join('material', 'material.idmaterial = item.materialid','left');    
        $this->db->join('department', 'department.iddepartment = item.departmentid','left');    
        $this->db->join('location', 'location.idlocation = item.locationid','left');    
        $this->db->join('users', 'users.id = item.userid','left');    
        $this->db->join('owner', 'owner.idowner = item.ownerid','left'); 
        $this->db->join('model', 'model.idmodel = item.modelid','left'); 
        $this->db->join('brand', 'model.brandid = brand.idbrand','left'); 
        $this->db->where('item.iditem', $id);
        $query = $this->db->get('item');

        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    public function showAllItems(){
        // $query = $this->db->get('material');

        $this->db->select('CONV(skeleton_item.iditem, 10, 36) AS "itemcodeid",item.iditem, item.item, category.category AS "cat", condition as "condition", material.material as "mat", department.department as "depat" , location.location as "locat", users.firstname AS "nameuser", owner.owner as "owner",status');
        $this->db->join('category', 'category.idcategory = item.categoryid','left');    
        $this->db->join('material', 'material.idmaterial = item.materialid','left');    
        $this->db->join('department', 'department.iddepartment = item.departmentid','left');    
        $this->db->join('location', 'location.idlocation = item.locationid','left');    
        $this->db->join('users', 'users.id = item.userid','left');    
        $this->db->join('owner', 'owner.idowner = item.ownerid','left'); 
        $query = $this->db->get('item');

        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }
     public function deleteItems($id){
        $this->db->where('iditem', $id);
        $this->db->delete('item');
    }

    // Model select category
    public function getAllCate()
    {  
        $query = $this->db->get('category');  
        if($query->num_rows() > 0)
        {   
            return $query->result();  
        }else{   
            return false;  
        } 
    }
    // Model select material
    public function getAllMat()
    {  
        $query = $this->db->get('material');  
        if($query->num_rows() > 0)
        {   
            return $query->result();  
        }else{   
            return false;  
        } 
    }

    // Model select department
    public function getAllDep()
    {  
        $query = $this->db->get('department');  
        if($query->num_rows() > 0)
        {   
            return $query->result();  
        }else{   
            return false;  
        } 
    }

    // Model select location
    public function getAllLoc()
    {  
        $query = $this->db->get('location');  
        if($query->num_rows() > 0)
        {   
            return $query->result();  
        }else{   
            return false;  
        } 
    }

    // Model select user
    public function getAllUser()
    {  
        $query = $this->db->get('users');  
        if($query->num_rows() > 0)
        {   
            return $query->result();  
        }else{   
            return false;  
        } 
    }

    // Model select owner
    public function getAllOwner()
    {  
        $query = $this->db->get('owner');  
        if($query->num_rows() > 0)
        {   
            return $query->result();  
        }else{   
            return false;  
        } 
    }


    // Model select brand
    public function getAllBrand()
    {  
        $query = $this->db->get('brand');  
        if($query->num_rows() > 0)
        {   
            return $query->result();  
        }else{   
            return false;  
        } 
    }


    // Model select model
    public function getAllModel($id)
    {  
        $this->db->where('brandid', $id);
        $query = $this->db->get('model');  
        if($query->num_rows() > 0)
        {   
            return $query->result();  
        }else{   
            return false;  
        } 
    }

    // get item maximum id with convert

    public function getmaxiditem(){
        $this->db->select('CONV(MAX(skeleton_item.iditem),10,36) AS "IdMax"');
        $query = $this->db->get('item');  
        if($query->num_rows() > 0)
        {   
            return $query->result();  
        }else{   
            return false;  
        } 
    }


    // get item id with convert

    public function getiditem($id){
        $this->db->select('CONV(skeleton_item.iditem,10,36) AS "IdMax"');
        $this->db->where('item.iditem',$id);
        $query = $this->db->get('item');  
        if($query->num_rows() > 0)
        {   
            return $query->result();  
        }else{   
            return false;  
        } 
    }


    // get location by id

    public function getLocById($id)
    {  
        $this->db->where('idlocation',$id);
        $query = $this->db->get('location');  
        if($query->num_rows() > 0)
        {   
            return $query->result();  
        }else{   
            return false;  
        } 
    }


   // Insert item to database 
    public function add_item($nameitem,$desitem,$catitem,$matitem,$depitem,$locitem,$moditem,$useritem,$ownitem,$conditionitem,$dateitem,$costitem,$code)
    {
        
        $data = array(
            'item'=> $nameitem,
            'itemdescription'=> $desitem,
            'categoryid'=> $catitem,
            'materialid'=> $matitem,
            'departmentid'=> $depitem,
            'locationid'=> $locitem,
            'modelid'=> $moditem,
            'userid'=> $useritem,
            'ownerid'=> $ownitem,
            'condition'=> $conditionitem,
            'date'=> $dateitem,
            'itemcost'=> $costitem,
            'status'=> '0',
            'code'=> $code
        );
        $this->db->query(' SET FOREIGN_KEY_CHECKS = 0');
        $query=$this->db->insert('item',$data);
        $this->db->query(' SET FOREIGN_KEY_CHECKS = 1');
        return $query;
    }
       // update item to database 
    public function update_item($nameitem,$desitem,$catitem,$matitem,$depitem,$locitem,$moditem,$useritem,$ownitem,$conditionitem,$dateitem,$costitem,$code,$id)
    {
        
        $data = array(
            'item'=> $nameitem,
            'itemdescription'=> $desitem,
            'categoryid'=> $catitem,
            'materialid'=> $matitem,
            'departmentid'=> $depitem,
            'locationid'=> $locitem,
            'modelid'=> $moditem,
            'userid'=> $useritem,
            'ownerid'=> $ownitem,
            'condition'=> $conditionitem,
            'date'=> $dateitem,
            'itemcost'=> $costitem,
            'status'=> '0',
            'code'=> $code
        );
        $this->db->where('item.iditem',$id);
        $this->db->set($data);
        $this->db->query(' SET FOREIGN_KEY_CHECKS = 0');
        $result = $this->db->update('item');
        $this->db->query(' SET FOREIGN_KEY_CHECKS = 1');
        return $result;
    }

        public function showBorrower(){
          $this->db->select('users.firstname');
          $this->db->from('users');
          $query = $this->db->get();

          if($query->num_rows() > 0){
             return $query->result();
         }else{
             return false;
         }
     }

    // use to get value of user for borrow item by id
     public function showUser()
     {
        $this->db->select('id, CONCAT(skeleton_users.firstname," ",skeleton_users.lastname) AS "borrower"');
        $sql = $this->db->get('users');
        if($sql->num_rows() > 0){
          return $sql->result();
      }else{
          return false;
      }
    }

    // use to get list of borrower by id
    public function showListBorrower($id){
      $this->db->select('item.iditem, item.item');    
      $this->db->where('iditem', $id);
      $query = $this->db->get('item');
      if($query->num_rows() > 0){
          return $query->result();
      }else{
          return false;
      }
    }

    //  This function use for insert data into borrower table in database 

    public function insertBorrow($borrower, $item, $startDate, $returnDate)
    {
        $data = array(
            'borrower'=> $borrower,
            'itemBorrow' => $item,
            'startDate' => $startDate,
            'returnDate' => $returnDate
        );
        $query=$this->db->insert('borrow',$data);
       
           $this->db->set('status', '1');
           $this->db->where('item.iditem',$item);
           $this->db->update('item');
        // $this->db->where('item.iditem',$item);
        // return  $this->db->update('item', $update);
           return $query;
    }

    // use to get list of return item by id
    public function returnitem($id){
        $queryStatement = "select * from skeleton_borrow WHERE skeleton_borrow.idBorrow in (select MAX(skeleton_borrow.idBorrow) from skeleton_borrow where skeleton_borrow.itemBorrow =".$id.')';
        $query= $this->db->query($queryStatement); 
        //return $query->result(); 
      // $this->db->select('borrower,MAX(skeleton_borrow.idBorrow) as "maxId",skeleton_borrow.startDate AS "startDate", borrow.returnDate' );    
      // $this->db->where in('idBorrow', $this->getm);
      // $this->db->where('itemBorrow', $id);
      // $query = $this->db->get('borrow');
      if($query->num_rows() > 0){
          return $query->result();
      }else{
          return false;
      }
    }

    public function getMaxIdBorrow($id){
        $this->db->select('MAX(skeleton_borrow.idBorrow) as "maxIdBorrow"');
        $this->db->where('itemBorrow',$id);
        $query = $this->db->get('borrow');
        return $query->result();
    }

// use to return and update status 

    public function r_u_status($data)
    {   

        $this->db->set('status', '0');
        $this->db->where('item.iditem',$data['itemId']);
        $s_update = $this->db->update('item');

        $this->db->set('actualDate',$data['actualDate']);
        $this->db->where('itemBorrow',$data['itemId']);
        $this->db->where('startDate',$data['startDate']);
        $this->db->where('idBorrow',$data['maxIdBorrow']);
        $this->db->update('borrow');

        return $s_update;
    }

// function to select expected return date to make condition in late status to show in the item list 
    // public function selectExDate()
    // {
    //     $this->db->select('returnDate' );
    //    // $this->db->where('itemBorrow', $id);
    //    $query = $this->db->get('borrow');
    //    if($query->num_rows() > 0){

    //        return $query->result();
    //    }else{
    //        return false;
    //    }
    // }

    public function returnLate()
    {
       // $query= $this->db->query('select max(skeleton_borrow.returnDate) AS reDate, iditem from skeleton_borrow inner join skeleton_item where skeleton_item.iditem = skeleton_borrow.itemBorrow and skeleton_item.`status`=1'); 
       // return $query->result();  
        $this->db->select('itemBorrow');
        $this->db->from('borrow');
        $this->db->where('actualDate',null);
        $this->db->where('returnDate <',date('Y/m/d'));
        $query = $this->db->get();
        return $query->result();
        // if($query->num_rows() > 0){
        //      return $query->result();
        //  }else{
        //      return false;
        // }
   }
   public function updateStatus($id){
        $this->db->query("update skeleton_item set skeleton_item.status = '2' where skeleton_item.iditem = '".$id."'");
   }
}
