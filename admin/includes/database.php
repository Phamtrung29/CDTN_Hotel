<?php
if(!defined('_CODE')){
    die('Access denied....');
}


function query($sql, $data=[], $check = false){
    global $conn;
    $ketqua = false;
    try{
        $statement = $conn->prepare($sql);
        if(!empty($data)){
            $ketqua = $statement->execute($data);   
        }
        else{
            $ketqua = $statement->execute();
        }
    }catch(Exception $exp){
        echo $exp->getMessage().'<br>';
        echo 'File: '. $exp ->getFile().'<br>';
        echo 'Line: '. $exp ->getLine();   
        die();
    }   
    if($check){
        return $statement;
    }
    return $ketqua;
}

//Hàm INSERT
function insert($table, $data){
    $key = array_keys($data);
    $truong = implode(',',$key);
    $valuetb = ':'.implode(',:',$key);

    $sql = 'INSERT INTO '. $table . '('.$truong.')' . 'VALUES('. $valuetb.')' ;  
    $kqua = query($sql, $data);
    return $kqua;
}

//Hàm UPDATE

function update($table, $data, $condition = ''){
    $update = '';
    foreach($data as $key => $value){
        $update .= $key .'= :' . $key .',';
    }
    $update = trim($update, ',');

    $sql = 'UPDATE '. $table .' SET '. $update .' WHERE '. $condition;
    $kqua = query($sql, $data);
    return $kqua;
}

function delete($table, $condition=''){
    if(empty($condition)){
        $sql = 'DELETE FROM ' . $table;
    } else{
        $sql = 'DELETE FROM ' . $table . ' WHERE '. $condition; 
    }
    $kqua = query($sql);
    return $kqua;
}

//Lấy dữ liệu
function getRaw ($sql){
    $kq = query($sql,'',true);
    if(is_object($kq)){
        $dataFetch = $kq -> fetchALL(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}
//Lấy 1 dòng dữ liệu
function oneRaw($sql){
    $kq = query($sql,'',true);
    if(is_object($kq)){
        $dataFetch = $kq -> fetch(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}
//Đếm số dòng dữ liệu
function getRows($sql){
    $kq = query($sql,'',true);
    if(!empty($kq)){
        return $kq -> rowCount();
    }
}

function getRow($sql){
    $kq = query($sql, '', true);
    if (is_object($kq)) {
        return $kq->rowCount();
    }
    return 0; // Trường hợp không có kết quả trả về
}
function getRaws($sql){
    $result = query($sql, '', true);
    if ($result && is_object($result)) {
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    return []; // Trường hợp không có kết quả trả về
}

function getNewAlerts() {
    global $conn; // Kết nối đến cơ sở dữ liệu

    try {
        // Truy vấn cơ sở dữ liệu để lấy thông báo mới
        $sql = "SELECT * FROM alerts WHERE status = 'new'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Lấy kết quả
        $new_alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $new_alerts;
    } catch (PDOException $e) {
        // Xử lý ngoại lệ nếu có lỗi
        echo "Error: " . $e->getMessage();
        return false;
    }
}