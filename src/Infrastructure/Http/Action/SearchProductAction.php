<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Http\Action;

use App\Domain\Repository\ProductRepository;
use App\Infrastructure\Http\Form\Model\SearchProduct;
use App\Infrastructure\Http\Form\Type\SearchProductFormType;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Error\Error;

final class SearchProductAction
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var SeoPageInterface
     */
    private $seoPage;

    public function __construct(
        Environment $twig,
        RouterInterface $router,
        TranslatorInterface $translator,
        FormFactoryInterface $formFactory,
        ProductRepository $productRepository,
        SeoPageInterface $seoPage
    ) {
        $this->twig               = $twig;
        $this->router             = $router;
        $this->translator         = $translator;
        $this->formFactory        = $formFactory;
        $this->productRepository  = $productRepository;
        $this->seoPage            = $seoPage;
    }

    /**
     * @Route("/search", name="search_product")
     *
     * @throws Error
     */
    public function __invoke(Request $request): Response
    {
        $this->addSeoInformation();

        // Data
        $formModel = new SearchProduct();

        $form = $this->formFactory->createNamed('search', SearchProductFormType::class, $formModel, [
            'action'            => $this->generateUrl('search_product'),
            'method'            => 'GET',
            'csrf_protection'   => false,
            'validation_groups' => ['SearchProduct', 'Default'],
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $formModel->setQuery(null);
        }

        $pager = $this->productRepository->search($formModel->getQuery() ?? '', (int) $request->get('page', 1));

        return new Response($this->twig->render('Product/search.html.twig', [
            'pager'  => $pager,
            'filter' => $formModel,
        ]));
    }

    private function addSeoInformation(): void
    {
        $this->seoPage
            ->addTitle($this->trans('search_product.meta_title'))
            ->addMeta('property', 'og:title', $this->trans('search_product.meta_title'))
            ->addMeta('name', 'description', $this->trans('search_product.meta_description'))
            ->addMeta('property', 'og:description', $this->trans('search_product.meta_description'))
            ->addMeta('property', 'og:type', 'website')
            ->addMeta('property', 'og:url', $this->generateUrl('search_product', [], UrlGeneratorInterface::ABSOLUTE_URL))
        ;
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route         The name of the route
     * @param array  $parameters    An array of parameters
     * @param int    $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     */
    private function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    private function trans(string $id, array $parameters = [], ?string $domain = 'App', ?string $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
