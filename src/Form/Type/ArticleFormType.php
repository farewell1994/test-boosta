<?php

namespace App\Form\Type;

use App\Entity\Article;
use Symfony\Component\Form\{
    AbstractType, FormBuilderInterface
};
use App\Validator\IsValidFile;
use Symfony\Component\Form\Extension\Core\Type\{
    FileType, SubmitType, TextareaType, TextType
};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class ArticleFormType
 * @package App\Form\Type
 */
class ArticleFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("author", TextType::class)
            ->add("title", TextType::class)
            ->add("description", TextareaType::class, [
                "required" => false
            ])
            ->add("file", FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(["maxSize" => "1024k",]),
                    new IsValidFile(),
                ]
            ])
            ->add("save", SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     * @return OptionsResolver|void
     */
    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        return $resolver->setDefaults([
            "data_class" => Article::class,
        ]);
    }
}
