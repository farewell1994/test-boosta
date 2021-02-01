<?php

namespace App\Form\Type;

use App\Entity\Article;
use Symfony\Component\Form\{
    AbstractType, FormBuilderInterface, FormEvent, FormEvents
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
            ->add("author", TextType::class, [
                "required" => false,
                "label" => "article.author",
                "help" => "article.required_fields_hint",
            ])
            ->add("title", TextType::class, [
                "required" => false,
                "label" => "article.title",
                "help" => "article.required_fields_hint",
            ])
            ->add("description", TextareaType::class, [
                "required" => false,
                "label" => "article.description",
            ])
            ->add("file", FileType::class, [
                'mapped' => false,
                "label" => "article.file",
                "help" => "article.upload_hint",
                'required' => false,
                'constraints' => [
                    new File(["maxSize" => "1024k",]),
                    new IsValidFile(),
                ]
            ])
            ->add("save", SubmitType::class, [
                "label" => "article.submit"
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $model = $event->getForm()->getData();
            // in order to correctly validate author and title
            // they are required in form fields if file does not exists
            $model->setFromFile((bool) $data["file"]);
        });
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
