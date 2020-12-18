<?php

namespace App\Controllers;

use App\Models\Foto_model;
use CodeIgniter\CLI\Console;
use CodeIgniter\HTTP\Request;

class Home extends BaseController
{
	protected $fotoModel;

	public function __construct()
	{
		$this->fotoModel = new Foto_model();
	}

	public function index()
	{
		return view('beranda');
	}

	public function ambilFoto()
	{
		$path = 'upload/';

		$file_list = array();
		$database = $this->fotoModel->orderBy('nama_foto', 'asc')->findAll();

		if (!empty($database)) {

			foreach ($database as $key => $value) {
				$file = $value['nama_foto'];
				$file_path =  $path . $file;
				if(file_exists($file_path)){
					var_dump($size);
					$file_list[] = array('name' => $file, 'size' => $size, 'path' => base_url('upload') . '/' . $file);
				}else{
					$file = 'not_found.png';
					$size = filesize($path . $file);
					$file_list[] = array('name' => $file, 'size' => $size, 'path' => base_url('upload').'/'.$file);
				}

			}

			$data = [
				'file_list'	=> $file_list,
				'database'	=> $database
			];
		} else {
			$data = [
				'empty' => "data empty",
			];
		}

		return json_encode($data);
		// $data = [
		// 	'foto'		=> $foto,
		// 	'path'		=> $path,
		// ];

		// return json_encode($foto);
	}



	public function deleteFoto($token)
	{
		$namafile = $this->fotoModel->select('nama_foto, id')->where('token', $token)->get()->getRowArray();

		if(file_exists(FCPATH . '/upload/' . $namafile['nama_foto'])){
			unlink(FCPATH . '/upload/' . $namafile['nama_foto']);
			$delete = $this->fotoModel->delete($namafile['id']);
		}
	
		$delete = $this->fotoModel->delete($namafile['id']);

		
		// unlink(FCPATH . '/upload/' . $namafile['nama_foto']);

		if ($delete) {
			$data['status'] = 200;
			return json_encode($data);
		} else {
			$data['status'] = 500;
			return json_encode($data);
		}
	}

	public function process_upload()
	{
		$path = FCPATH . 'upload/';
		// var_dump($_POST);
		// die();
		// var_dump($_FILES);
		// var_dump($this->request->getPost('token'));
		$tempfile = $_FILES['file']['tmp_name'];
		$imagename = $_FILES['file']['name'];
		$extension = pathinfo($imagename, PATHINFO_EXTENSION);
		$filename = uniqid(rand(), true) . '.' . $extension;
		// var_dump($filename);
		// die();
		$token = $this->request->getPost('token');
		move_uploaded_file($tempfile, $path . $filename);
		// $gambar = $this->request->getFile('userfile');
		// $gambar->move(ROOTPATH . 'upload/');

		// $nama = $gambar->getName();

		$data = [
			'nama_foto' => $filename,
			'token'		=> $token
		];

		$this->fotoModel->uplooad_foto($data);
	}

	public function remove_foto()
	{
	}

	//--------------------------------------------------------------------

}
