<?php

namespace App\Form;

use App\Entity\Manga\Manga;
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
			])
			->add('genre', TextType::class, [
				'required' => false,
			])
			->add('author', TextType::class, [
				'required' => false,
			]);
	}

	public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
			'data_class' => Manga::class,
            'method' => 'GET',
        ]);
    }
}