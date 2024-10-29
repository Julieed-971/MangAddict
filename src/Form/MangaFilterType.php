<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MangaFilterType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('type', TextType::class, [
				'required' => false,
				'label' => 'Type de manga',
			])
			->add('genre', ChoiceType::class, [
				'required' => false,
				'label' => 'Genres',
				'choices' => $options['genres'],
				'multiple' => true,
				'expanded' => true,
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
			'genres' => [],
        ]);
    }
}