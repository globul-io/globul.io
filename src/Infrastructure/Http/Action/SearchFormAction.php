<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Http\Action;

use App\Infrastructure\Http\Form\Model\SearchProduct;
use App\Infrastructure\Http\Form\Type\SearchProductFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Error\Error;

class SearchFormAction
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
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(Environment $twig, RouterInterface $router, FormFactoryInterface $formFactory)
    {
        $this->twig        = $twig;
        $this->router      = $router;
        $this->formFactory = $formFactory;
    }

    /**
     * @throws Error
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->createNamed('search', SearchProductFormType::class, new SearchProduct(), [
            'action'            => $this->generateUrl('search_product'),
            'method'            => 'GET',
            'csrf_protection'   => false,
            'validation_groups' => ['SearchProduct', 'Default'],
        ]);

        return new Response($this->twig->render('Product/form.html.twig', [
            'form'   => $form->createView(),
        ]));
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
}
