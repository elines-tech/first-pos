<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library("smstwilio");
		$this->load->library("smsplivo");
		$this->load->library("emailer");
	}

	public function send_plivo_sms()
	{
		$config = [
			"authId" => "",
			"authToken" => "",
			"senderId" => ""
		];
		$this->smsplivo->transact($config, "+918983185204", "This is sample message sedn by plivo and ci-library");
	}

	public function send_twilio_sms()
	{
		$config = [
			"sid" => "ACdccde8ddd4cf0cc39fffe7b0e8880eb4",
			"token" => "8b6dca04c61a1b9138adf1d79ee21dce",
			"twilionumber" => "+12059904947"
		];
		$this->smstwilio->transact($config, "+918983185204", "This is sample message sedn by twilio and ci-library");
	}

	public function sendmail()
	{
		$config = [
			"maildriver" => "localhost",
			"host" => "click2courier.com",
			"port" => "465",
			"username" => "kaemtrial@click2courier.com",
			"password" => "kaemsoftware",
			"encryption" => "",
			"fromaddress" => "kaemtrial@click2courier.com",
			"fromname" => "KAEM INFO"
		];
		$subject = "Mail with attachment";
		$message = "Aliquid? Senectus ab porro egestas facere dolor! Aspernatur, dignissim ut iaculis voluptatibus, imperdiet pretium justo veniam? Nostrud habitasse, reiciendis, taciti sem quam placeat porro dignissim euismod nostrud magnis voluptatibus eaque fermentum ipsam proin nihil mus, cum. Dolor aliquip id voluptates eius fermentum, lectus! Cillum habitasse? Nisi mollit phasellus! Praesent cupiditate sem odio veniam fuga, ad, ullam purus, consequat parturient fames, sit ultricies, commodo, diamlorem, adipisicing magna placeat facere? Blanditiis. Explicabo justo rem, aliquid lorem voluptas? Doloribus, diamlorem consequat laudantium excepteur, vitae, urna veniam, leo! Voluptates montes, leo molestiae bibendum cupidatat labore taciti! Eligendi laboris tempore? Magnis, aenean tempore felis cubilia.";
		$attachment = [
			FCPATH . "sample.png"
		];
		echo $this->emailer->transact($config, "abhiharshe1191@gmail.com", $subject, $message, $attachment);
	}



	public function index()
	{
	
	}


}
