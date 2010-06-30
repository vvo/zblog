<?php

// We directly load the functions we are often going to use in the
// views. This makes the code cleaner.
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

class Zblog_Views {

	public function main($request, $match) {
		$context = new Pluf_Template_Context(array('page_title' => 'Réduisez le temps de chargement, gagnez de nouveaux clients'));
		$tmpl = new Pluf_Template('zblog/homepage.html');
		return new Pluf_HTTP_Response($tmpl->render($context));
	}

	public function prestations($request, $match) {
		$context = new Pluf_Template_Context(array('page_title' => 'amélioration du temps de chargement de votre site'));
		$tmpl = new Pluf_Template('zblog/prestations.html');
		return new Pluf_HTTP_Response($tmpl->render($context));
	}

	public function about($request, $match) {
		Pluf::loadFunction('Pluf_Text_MarkDown_parse');
		$tpl_path = Pluf::f('template_folders');
		$tpl_path = realpath($tpl_path[0]);
		$text = Pluf_Text_MarkDown_parse(file_get_contents($tpl_path.'/zblog/a-propos.md'));
		$context = new Pluf_Template_Context(
				array(
					'page_title' => 'à propos',
					'text' => $text
					)
		);
		$tmpl = new Pluf_Template('zblog/a-propos.html');
		return new Pluf_HTTP_Response($tmpl->render($context));
	}

	public function contact($request, $match) {
		if ($request->method == 'POST') {
			$form = new Zblog_Form_Contact($request->POST);
//			var_dump($form->errors);
			if($form->isValid()) {
				$email = new Pluf_Mail($form->cleaned_data['email'],
						Pluf::f('mail_to'),
						'Message depuis zeroload.net');
				$email->addTextMessage(
						"Message de : ".$form->cleaned_data['name']." ".$form->cleaned_data['email']."

Texte du message :

".$form->cleaned_data['message']."

Vous pouvez utiliser la fonction répondre de votre lecteur de mail");
				$email->sendMail();

				$url = Pluf_HTTP_URL_urlForView('Zblog_Views::contact_sent');
				return new Pluf_HTTP_Response_Redirect($url);
			}
		}

		$context = new Pluf_Template_Context(array('page_title' => 'devis performance gratuit'));
		$tmpl = new Pluf_Template('zblog/contact.html');
		return new Pluf_HTTP_Response($tmpl->render($context));
	}

	public function contact_sent($request, $match) {
		return Pluf_Shortcuts_RenderToResponse('zblog/contact-sent.html',
				array('page_title' => 'Merci'));
	}
}
