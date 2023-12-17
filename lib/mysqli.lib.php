<?php



class mysqli_lib{
    
    public $query_count = 0;
    public $last_query = null;
    public $query_list = array();
    private $link = null;
        
    function connect($credentials)
    {
        if (!is_array($credentials))
        {
            return;
        }
        
        $host = $credentials['host'];
        $user = $credentials['user'];
        $pass = $credentials['password'];
        $database = $credentials['database'];
        
        $this->link = mysqli_connect($host, $user, $pass, $database);
        
        if (mysqli_connect_errno()) 
        {
            Return false;
        }
        
        return $this->link;
    }

    function query($string)
    {
        $query = @mysqli_query($this->link, $string);
        
        If ($query)
        {
            $this->query_list[$this->query_count] = $string;
            $this->last_query = $string;
            $this->query_count++;
        }
                
        return $query;
    }
    
    function fetch_array($query)
    {
  		$array = mysqli_fetch_assoc($query);
        
		return $array;
    }
    
	function data_seek($query, $row)
	{
		return mysqli_data_seek($query, $row);
	}
    
	function fetch_field($query, $field, $row=false)
	{
		if($row !== false)
		{
			$this->data_seek($query, $row);
		}
		$array = $this->fetch_array($query);
		return $array[$field];
	}
    
    function select($table, $fields, $conditions = "", $options = array())
    {
        
        $query = "SELECT ".$fields." FROM ".$table;
        
		if($conditions != "")
		{
			$query .= " WHERE ".$conditions;
		}
        
        if (isset($options['order']))
        {
            $query .= " ORDER BY ".$options['order'];
        }
        
        if (isset($options['limit']))
        {
            $query .= " LIMIT ".$options['limit'];
        }
        
        return $this->query($query);
        
    }

    function delete($table, $conditions = "", $limit = "")
    {
        
        $query = "DELETE FROM ".$table;
        
        If ($conditions != "")
        {
            $query .= " WHERE ".$conditions;
        }
        
        If ($limit != "")
        {
            $query .= " LIMIT ".$limit;
        }
        
        return $this->query($query);
        
    }
    
    function update($table, $array, $conditions = "", $limit = "", $quote = true)
    {
        if (!is_array($array))
        {
            return false;
        }
        
        $virgule = "";
        $query = "";
        $quote = "'";
        
        if ($quote == false)
        {
            $quote = "";
        }
        
		foreach($array as $field => $value)
		{
			$query .= $virgule."`".$field."`={$quote}{$value}{$quote}";
			$virgule = ', ';
		}   
        
        if ($conditions != "")
        {
            $query .= " WHERE ".$conditions;
        }     
        
        if ($limit != "")
        {
            $query .= " LIMIT ".$limit;
        }
        
        return $this->query("UPDATE $table SET $query");   
    }
    
	function replace($table, $replacements=array())
	{
		$values = '';
		$comma = '';
		foreach($replacements as $column => $value)
		{
			$values .= $comma."`".$column."`='".$value."'";
			
			$comma = ',';
		}
		
		if(empty($replacements))
		{
			 return false;
		}
		
		return $this->query("REPLACE INTO {$table} SET {$values}");
	}
    
	function insert_id()
	{
		$id = mysqli_insert_id($this->link);
		return $id;
	}
    
	function insert($table, $array)
	{
		if(!is_array($array))
		{
			return false;
		}
		$fields = "`".implode("`,`", array_keys($array))."`";
		$values = implode("','", $array);
		$this->query("
			INSERT 
			INTO {$table} (".$fields.") 
			VALUES ('".$values."')
		");
		return $this->insert_id();
	}
         
    function filter($data, $html = true) 
    {
        If ($html == true)
        {
            $data = trim(htmlentities(strip_tags($data)));
        }
        
    	if (get_magic_quotes_gpc())
    		$data = stripslashes($data);
    	
    	$data = mysqli_real_escape_string($this->link, $data);
    	
    	return $data;
    }
        
    function num_rows($query)
    {
        return mysqli_num_rows($query);
    }
    
	function table_exists($table)
	{
		$query = $this->query("
			SHOW TABLES 
			LIKE '$table'");
		$exists = $this->num_rows($query);
		
		if($exists > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
    
	function affected_rows()
	{
		return mysqli_affected_rows($this->link);
	}
        
    function error()
    {
        If (!empty($this->link))
        {
            return mysqli_error($this->link);
        }else{
            return "No link";
        }        
    }
    
    function close()
    {
        @mysqli_close($this->link);
    }
        
}

?>