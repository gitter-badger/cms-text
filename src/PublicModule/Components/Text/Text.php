<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @package     Wunderman\CMS\Text
 */

namespace Wunderman\CMS\Text\PublicModule\Components\Text;

use Nette\Application\UI\Control;
use Kdyby\Doctrine\EntityManager;
use App\Entity\Attachment;
use App\Entity\TextArticle;

class Text extends Control
{

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var TextArticle
	 */
	private $textRepository;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->textRepository = $em->getRepository(TextArticle::class);
	}


	/**
	 * @var array $textId
	 */
	public function render($textId)
	{
		$template = 'default.latte';

		if (is_int($textId))
		{
			$this->getTemplate()->article = $this->textRepository->findOneBy(array('id' => $textId));
		}
		elseif (is_array($textId))
		{
			$this->getTemplate()->article = $this->textRepository->findOneBy(array('id' => $textId['id']));

			if (isset($textId['template']))
			{
				$template = $textId['template'];
			}
		}
		elseif ($textId === 'homepage')
		{
			$this->getTemplate()->article = $this->textRepository->findOneBy(array('homepage' => 1));
		}

		$templateBase = __DIR__."/templates/";
		$templatePath = $templateBase . $template;

		if (!file_exists($templatePath))
		{
			$templatePath = $templateBase . 'default.latte';
		}

		$this->getTemplate()->setFile(__DIR__."/templates/{$template}");
		$this->getTemplate()->render();
	}

}
