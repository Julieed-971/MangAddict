<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MangaFilterType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('type', TextType::class, [
				'required' => false,
				'label' => 'Type de manga',
			])
			->add('genre', TextType::class, [
				'required' => false,
				'label' => 'Genres',
			])
			->add('author', TextType::class, [
				'required' => false,
				'label' => 'Auteur-Autrice',

			]);
	}

	public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
			'data_class' => null,
			'block_name' => 'manga_filter',
        ]);
    }
}