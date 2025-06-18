<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Enum\Level;
use App\Entity\Project;
use App\Entity\ProjectTag;
use App\Entity\ProjectTechnology;
use App\Entity\Tag;
use App\Entity\Technology;
use App\Form\EventSubscriber\ProjectSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ProjectType extends AbstractType
{
    public const TYPE_ADD = 'add';
    public const TYPE_EDIT = 'edit';

    public function __construct(
        private ProjectSubscriber $projectSubscriber,
        private EntityManagerInterface $entityManager,
        private TranslatorInterface $translator,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $project = $options['data'];

        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'projects.form.title_placeholder'],
            ])
            ->add('summary', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'projects.form.summary_placeholder'],
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'projects.form.description_placeholder',
                    'rows' => 5,
                ],
            ])
            ->add('technologies', EntityType::class, [
                'label' => false,
                'class' => Technology::class,
                'mapped' => false,
                'choice_label' => 'name',
                'choices' => $this->getOrderedTechnologyChoices($project),
                'autocomplete' => true,
                'data' => array_map(function (ProjectTechnology $projectTechnology) {
                    return $projectTechnology->getTechnology();
                }, $project->getProjectTechnologies()->toArray()),
                'multiple' => true,
                'tom_select_options' => [
                    'placeholder' => $this->translator->trans('projects.form.technologies_placeholder', [], 'admin'),
                    'hidePlaceholder' => true,
                ],
            ])
            ->add('tags', EntityType::class, [
                'label' => false,
                'class' => Tag::class,
                'mapped' => false,
                'choice_label' => 'name',
                'choices' => $this->getOrderedTagChoices($project),
                'autocomplete' => true,
                'data' => array_map(function (ProjectTag $projectTag) {
                    return $projectTag->getTag();
                }, $project->getProjectTags()->toArray()),
                'multiple' => true,
                'tom_select_options' => [
                    'placeholder' => $this->translator->trans('projects.form.tags_placeholder', [], 'admin'),
                    'hidePlaceholder' => true,
                ],
            ])
            ->add('companies', EntityType::class, [
                'label' => false,
                'class' => Company::class,
                'choice_label' => 'name',
                'autocomplete' => true,
                'multiple' => true,
                'tom_select_options' => [
                    'create' => true,
                    'placeholder' => $this->translator->trans('projects.form.companies_placeholder', [], 'admin'),
                    'hidePlaceholder' => true,
                ],
            ])
            ->add('level', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'projects.form.level_placeholder',
                'autocomplete' => true,
                'choice_loader' => new CallbackChoiceLoader(function (): array {
                    $choices = [];

                    foreach (Level::cases() as $case) {
                        $label = $this->translator->trans('enum.level.' . strtolower($case->name), [], 'admin');
                        $choices[$label] = $case;
                    }

                    return $choices;
                }),
            ])
            ->add('pictures', DropzoneType::class, [
                'label' => 'projects.form.pictures',
                'mapped' => false,
                'multiple' => true,
            ])
        ;

        $builder->addEventSubscriber($this->projectSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
            'translation_domain' => 'admin',
            'type' => self::TYPE_ADD,
        ]);

        $resolver->setAllowedValues('type', [self::TYPE_ADD, self::TYPE_EDIT]);
    }

    /**
     * @return array<int, Technology>
     */
    private function getOrderedTechnologyChoices(Project $project): array
    {
        $projectTechnologies = array_map(function (ProjectTechnology $projectTechnology) {
            return $projectTechnology->getTechnology();
        }, $project->getProjectTechnologies()->toArray());

        $technologies = $this->entityManager->getRepository(Technology::class)->findAll();
        $remainingTechnologies = array_filter($technologies, function (Technology $technology) use ($projectTechnologies) {
            return !in_array($technology, $projectTechnologies, true);
        });

        return array_merge($projectTechnologies, $remainingTechnologies);
    }

    /**
     * @return array<int, Tag>
     */
    private function getOrderedTagChoices(Project $project): array
    {
        $projectTags = array_map(function (ProjectTag $projectTag) {
            return $projectTag->getTag();
        }, $project->getProjectTags()->toArray());

        $tags = $this->entityManager->getRepository(Tag::class)->findAll();
        $remainingTags = array_filter($tags, function (Tag $tag) use ($projectTags) {
            return !in_array($tag, $projectTags, true);
        });

        return array_merge($projectTags, $remainingTags);
    }
}
