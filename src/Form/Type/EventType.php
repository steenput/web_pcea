<?php

namespace Pcea\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;

class EventType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('name', TextType::class)
			->add('currency', CurrencyType::class);
	}

	public function getName() {
		return 'event';
	}

}
