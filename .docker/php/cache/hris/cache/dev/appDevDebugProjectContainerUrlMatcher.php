<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appDevDebugProjectContainerUrlMatcher.
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appDevDebugProjectContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        if (0 === strpos($pathinfo, '/_')) {
            // _wdt
            if (0 === strpos($pathinfo, '/_wdt') && preg_match('#^/_wdt/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_wdt')), array (  '_controller' => 'web_profiler.controller.profiler:toolbarAction',));
            }

            if (0 === strpos($pathinfo, '/_profiler')) {
                // _profiler_home
                if (rtrim($pathinfo, '/') === '/_profiler') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_profiler_home');
                    }

                    return array (  '_controller' => 'web_profiler.controller.profiler:homeAction',  '_route' => '_profiler_home',);
                }

                if (0 === strpos($pathinfo, '/_profiler/search')) {
                    // _profiler_search
                    if ($pathinfo === '/_profiler/search') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchAction',  '_route' => '_profiler_search',);
                    }

                    // _profiler_search_bar
                    if ($pathinfo === '/_profiler/search_bar') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchBarAction',  '_route' => '_profiler_search_bar',);
                    }

                }

                // _profiler_phpinfo
                if ($pathinfo === '/_profiler/phpinfo') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:phpinfoAction',  '_route' => '_profiler_phpinfo',);
                }

                // _profiler_search_results
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/search/results$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_search_results')), array (  '_controller' => 'web_profiler.controller.profiler:searchResultsAction',));
                }

                // _profiler_open_file
                if ($pathinfo === '/_profiler/open') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:openAction',  '_route' => '_profiler_open_file',);
                }

                // _profiler
                if (preg_match('#^/_profiler/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler')), array (  '_controller' => 'web_profiler.controller.profiler:panelAction',));
                }

                // _profiler_router
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/router$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_router')), array (  '_controller' => 'web_profiler.controller.router:panelAction',));
                }

                // _profiler_exception
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception')), array (  '_controller' => 'web_profiler.controller.exception:showAction',));
                }

                // _profiler_exception_css
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception\\.css$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception_css')), array (  '_controller' => 'web_profiler.controller.exception:cssAction',));
                }

            }

            // _twig_error_test
            if (0 === strpos($pathinfo, '/_error') && preg_match('#^/_error/(?P<code>\\d+)(?:\\.(?P<_format>[^/]++))?$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_twig_error_test')), array (  '_controller' => 'twig.controller.preview_error:previewErrorPageAction',  '_format' => 'html',));
            }

        }

        if (0 === strpos($pathinfo, '/alphalist')) {
            if (0 === strpos($pathinfo, '/alphalist/generate/annual')) {
                // hris_payroll_generate_annual_index
                if ($pathinfo === '/alphalist/generate/annual') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_generate_annual_index;
                    }

                    return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxGenerateController::indexAction',  '_route' => 'hris_payroll_generate_annual_index',);
                }
                not_hris_payroll_generate_annual_index:

                // hris_payroll_generate_annual_submit
                if ($pathinfo === '/alphalist/generate/annual') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_generate_annual_submit;
                    }

                    return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxGenerateController::filterAction',  '_route' => 'hris_payroll_generate_annual_submit',);
                }
                not_hris_payroll_generate_annual_submit:

                // hris_annual_progress
                if ($pathinfo === '/alphalist/generate/annual/progress') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_annual_progress;
                    }

                    return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxGenerateController::getProgressAction',  '_route' => 'hris_annual_progress',);
                }
                not_hris_annual_progress:

            }

            if (0 === strpos($pathinfo, '/alphalist/a')) {
                if (0 === strpos($pathinfo, '/alphalist/annual')) {
                    // hris_payroll_annual_earning_add_ajax
                    if (0 === strpos($pathinfo, '/alphalist/annual/add_earning') && preg_match('#^/alphalist/annual/add_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_annual_earning_add_ajax;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_annual_earning_add_ajax')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxGenerateController::addEarningAction',));
                    }
                    not_hris_payroll_annual_earning_add_ajax:

                    // hris_payroll_annual_earning_delete
                    if (0 === strpos($pathinfo, '/alphalist/annual/delete_earning') && preg_match('#^/alphalist/annual/delete_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_annual_earning_delete;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_annual_earning_delete')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxGenerateController::deleteEarningAction',));
                    }
                    not_hris_payroll_annual_earning_delete:

                    // hris_payroll_annual_deduction_add_ajax
                    if (0 === strpos($pathinfo, '/alphalist/annual/add_deduction') && preg_match('#^/alphalist/annual/add_deduction/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_annual_deduction_add_ajax;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_annual_deduction_add_ajax')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxGenerateController::addDeductionAction',));
                    }
                    not_hris_payroll_annual_deduction_add_ajax:

                    // hris_payroll_annual_deduction_delete
                    if (0 === strpos($pathinfo, '/alphalist/annual/delete_deduction') && preg_match('#^/alphalist/annual/delete_deduction/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_annual_deduction_delete;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_annual_deduction_delete')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxGenerateController::deleteDeductionAction',));
                    }
                    not_hris_payroll_annual_deduction_delete:

                    // hris_payroll_annual_lock
                    if (0 === strpos($pathinfo, '/alphalist/annual/lock') && preg_match('#^/alphalist/annual/lock/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_annual_lock;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_annual_lock')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxGenerateController::lockAction',));
                    }
                    not_hris_payroll_annual_lock:

                    if (0 === strpos($pathinfo, '/alphalist/annual/details')) {
                        // hris_payroll_annual_details_print
                        if (preg_match('#^/alphalist/annual/details/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_payroll_annual_details_print;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_annual_details_print')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxGenerateController::printAction',));
                        }
                        not_hris_payroll_annual_details_print:

                        // hris_payroll_annual_details_index
                        if (preg_match('#^/alphalist/annual/details/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_payroll_annual_details_index;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_annual_details_index')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxGenerateController::detailsAction',));
                        }
                        not_hris_payroll_annual_details_index:

                    }

                    if (0 === strpos($pathinfo, '/alphalist/annual/review')) {
                        // hris_payroll_review_annual_index
                        if ($pathinfo === '/alphalist/annual/review') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_payroll_review_annual_index;
                            }

                            return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxReviewController::indexAction',  '_route' => 'hris_payroll_review_annual_index',);
                        }
                        not_hris_payroll_review_annual_index:

                        // hris_payroll_review_annual_lock_all
                        if (0 === strpos($pathinfo, '/alphalist/annual/review/lock') && preg_match('#^/alphalist/annual/review/lock/(?P<year>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_payroll_review_annual_lock_all;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_review_annual_lock_all')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxReviewController::lockAllAction',));
                        }
                        not_hris_payroll_review_annual_lock_all:

                    }

                    // hris_payroll_review_annual_grid
                    if ($pathinfo === '/alphalist/annual/view/grid') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_review_annual_grid;
                        }

                        return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxReviewController::gridAction',  '_route' => 'hris_payroll_review_annual_grid',);
                    }
                    not_hris_payroll_review_annual_grid:

                    if (0 === strpos($pathinfo, '/alphalist/annual/archives')) {
                        // hris_payroll_view_annual_index
                        if ($pathinfo === '/alphalist/annual/archives') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_payroll_view_annual_index;
                            }

                            return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxArchiveController::indexAction',  '_route' => 'hris_payroll_view_annual_index',);
                        }
                        not_hris_payroll_view_annual_index:

                        // hris_payroll_view_annual_grid
                        if ($pathinfo === '/alphalist/annual/archives/grid') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_payroll_view_annual_grid;
                            }

                            return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxArchiveController::gridAction',  '_route' => 'hris_payroll_view_annual_grid',);
                        }
                        not_hris_payroll_view_annual_grid:

                    }

                    if (0 === strpos($pathinfo, '/alphalist/annual_earning')) {
                        if (0 === strpos($pathinfo, '/alphalist/annual_earnings')) {
                            // hris_payroll_earning_annual_index
                            if ($pathinfo === '/alphalist/annual_earnings') {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_payroll_earning_annual_index;
                                }

                                return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::indexAction',  '_route' => 'hris_payroll_earning_annual_index',);
                            }
                            not_hris_payroll_earning_annual_index:

                            // hris_payroll_earning_annual_grid
                            if ($pathinfo === '/alphalist/annual_earnings/grid') {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_payroll_earning_annual_grid;
                                }

                                return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::gridAction',  '_route' => 'hris_payroll_earning_annual_grid',);
                            }
                            not_hris_payroll_earning_annual_grid:

                        }

                        // hris_payroll_earning_annual_add_form
                        if ($pathinfo === '/alphalist/annual_earning') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_payroll_earning_annual_add_form;
                            }

                            return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::addFormAction',  '_route' => 'hris_payroll_earning_annual_add_form',);
                        }
                        not_hris_payroll_earning_annual_add_form:

                        // hris_payroll_earning_annual_add_submit
                        if ($pathinfo === '/alphalist/annual_earning') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_payroll_earning_annual_add_submit;
                            }

                            return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::addSubmitAction',  '_route' => 'hris_payroll_earning_annual_add_submit',);
                        }
                        not_hris_payroll_earning_annual_add_submit:

                        // hris_payroll_earning_annual_edit_form
                        if (preg_match('#^/alphalist/annual_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_payroll_earning_annual_edit_form;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earning_annual_edit_form')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::editFormAction',));
                        }
                        not_hris_payroll_earning_annual_edit_form:

                        // hris_payroll_earning_annual_edit_submit
                        if (preg_match('#^/alphalist/annual_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_payroll_earning_annual_edit_submit;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earning_annual_edit_submit')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::editSubmitAction',));
                        }
                        not_hris_payroll_earning_annual_edit_submit:

                        // hris_payroll_earning_annual_delete
                        if (preg_match('#^/alphalist/annual_earning/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_payroll_earning_annual_delete;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earning_annual_delete')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::deleteAction',));
                        }
                        not_hris_payroll_earning_annual_delete:

                    }

                }

                if (0 === strpos($pathinfo, '/alphalist/ajax/annual_earning')) {
                    // hris_payroll_earning_annual_ajax_get
                    if (preg_match('#^/alphalist/ajax/annual_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_earning_annual_ajax_get;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earning_annual_ajax_get')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::ajaxGetAction',));
                    }
                    not_hris_payroll_earning_annual_ajax_get:

                    // hris_payroll_earning_annual_ajax_get_form
                    if (preg_match('#^/alphalist/ajax/annual_earning/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_earning_annual_ajax_get_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earning_annual_ajax_get_form')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::ajaxGetFormAction',));
                    }
                    not_hris_payroll_earning_annual_ajax_get_form:

                    // hris_payroll_earning_annual_ajax_add
                    if ($pathinfo === '/alphalist/ajax/annual_earning') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_earning_annual_ajax_add;
                        }

                        return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::ajaxAddAction',  '_route' => 'hris_payroll_earning_annual_ajax_add',);
                    }
                    not_hris_payroll_earning_annual_ajax_add:

                    // hris_payroll_earning_annual_ajax_save
                    if (preg_match('#^/alphalist/ajax/annual_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_earning_annual_ajax_save;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earning_annual_ajax_save')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::ajaxSaveAction',));
                    }
                    not_hris_payroll_earning_annual_ajax_save:

                    // hris_payroll_earning_annual_ajax
                    if (0 === strpos($pathinfo, '/alphalist/ajax/annual_earnings/details') && preg_match('#^/alphalist/ajax/annual_earnings/details/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_earning_annual_ajax;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earning_annual_ajax')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\AnnualizedTaxEarningController::ajaxDetailsAction',));
                    }
                    not_hris_payroll_earning_annual_ajax:

                }

            }

            if (0 === strpos($pathinfo, '/alphalist/settings')) {
                if (0 === strpos($pathinfo, '/alphalist/settings/employeeassignment')) {
                    // hris_payroll_employeeassignment_index
                    if ($pathinfo === '/alphalist/settings/employeeassignment') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_employeeassignment_index;
                        }

                        return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GovernmentFormsController::employeeassignmentIndexAction',  '_route' => 'hris_payroll_employeeassignment_index',);
                    }
                    not_hris_payroll_employeeassignment_index:

                    // hris_payroll_employeeassignment_submit
                    if ($pathinfo === '/alphalist/settings/employeeassignment') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_employeeassignment_submit;
                        }

                        return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GovernmentFormsController::employeeassignmentSubmitAction',  '_route' => 'hris_payroll_employeeassignment_submit',);
                    }
                    not_hris_payroll_employeeassignment_submit:

                }

                if (0 === strpos($pathinfo, '/alphalist/settings/taxableceiling')) {
                    // hris_payroll_taxableceiling_index
                    if ($pathinfo === '/alphalist/settings/taxableceiling') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_taxableceiling_index;
                        }

                        return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GovernmentFormsController::taxableceilingIndexAction',  '_route' => 'hris_payroll_taxableceiling_index',);
                    }
                    not_hris_payroll_taxableceiling_index:

                    // hris_payroll_taxableceiling_submit
                    if ($pathinfo === '/alphalist/settings/taxableceiling') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_taxableceiling_submit;
                        }

                        return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GovernmentFormsController::taxableceilingSubmitAction',  '_route' => 'hris_payroll_taxableceiling_submit',);
                    }
                    not_hris_payroll_taxableceiling_submit:

                }

            }

            if (0 === strpos($pathinfo, '/alphalist/generate/governmentForms')) {
                // hris_generate_government_forms_index
                if ($pathinfo === '/alphalist/generate/governmentForms') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_generate_government_forms_index;
                    }

                    return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GenerateGovernmentFormsController::indexAction',  '_route' => 'hris_generate_government_forms_index',);
                }
                not_hris_generate_government_forms_index:

                // hris_generate_government_forms_submit
                if ($pathinfo === '/alphalist/generate/governmentForms') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_generate_government_forms_submit;
                    }

                    return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GenerateGovernmentFormsController::filterAction',  '_route' => 'hris_generate_government_forms_submit',);
                }
                not_hris_generate_government_forms_submit:

            }

            // hris_government_forms_progress
            if ($pathinfo === '/alphalist/progress/governmentForms') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_government_forms_progress;
                }

                return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GenerateGovernmentFormsController::getProgressAction',  '_route' => 'hris_government_forms_progress',);
            }
            not_hris_government_forms_progress:

            if (0 === strpos($pathinfo, '/alphalist/view')) {
                if (0 === strpos($pathinfo, '/alphalist/view/governmentForms')) {
                    // hris_government_forms_view_index
                    if ($pathinfo === '/alphalist/view/governmentForms') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_government_forms_view_index;
                        }

                        return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GovernmentFormsViewController::indexAction',  '_route' => 'hris_government_forms_view_index',);
                    }
                    not_hris_government_forms_view_index:

                    // hris_government_forms_view_grid
                    if (0 === strpos($pathinfo, '/alphalist/view/governmentForms/grid') && preg_match('#^/alphalist/view/governmentForms/grid/(?P<type>[^/]++)/(?P<year>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_government_forms_view_grid;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_forms_view_grid')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GovernmentFormsViewController::gridFilterAction',));
                    }
                    not_hris_government_forms_view_grid:

                }

                if (0 === strpos($pathinfo, '/alphalist/view/details')) {
                    // hris_government_forms_details_index
                    if (preg_match('#^/alphalist/view/details/(?P<id>[^/]++)/(?P<link_type>[^/]++)/(?P<type>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_government_forms_details_index;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_forms_details_index')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GenerateGovernmentFormsController::detailsAction',));
                    }
                    not_hris_government_forms_details_index:

                    // hris_government_forms_details_submit
                    if (preg_match('#^/alphalist/view/details/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_government_forms_details_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_forms_details_submit')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GenerateGovernmentFormsController::editSubmitAction',));
                    }
                    not_hris_government_forms_details_submit:

                }

            }

            // hris_government_forms_csv
            if (0 === strpos($pathinfo, '/alphalist/governmentForms') && preg_match('#^/alphalist/governmentForms/(?P<type>[^/]++)/(?P<link_type>[^/]++)/(?P<year>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_government_forms_csv;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_forms_csv')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GenerateGovernmentFormsController::exportFormCSVAction',));
            }
            not_hris_government_forms_csv:

            if (0 === strpos($pathinfo, '/alphalist/review/governmentForms')) {
                // hris_government_forms_review_index
                if ($pathinfo === '/alphalist/review/governmentForms') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_government_forms_review_index;
                    }

                    return array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GovernmentFormsReviewController::indexAction',  '_route' => 'hris_government_forms_review_index',);
                }
                not_hris_government_forms_review_index:

                // hris_government_forms_review_grid
                if (0 === strpos($pathinfo, '/alphalist/review/governmentForms/grid') && preg_match('#^/alphalist/review/governmentForms/grid/(?P<type>[^/]++)/(?P<year>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_government_forms_review_grid;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_forms_review_grid')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GovernmentFormsReviewController::gridFilterAction',));
                }
                not_hris_government_forms_review_grid:

            }

            if (0 === strpos($pathinfo, '/alphalist/lock')) {
                // hris_government_forms_lock
                if (preg_match('#^/alphalist/lock/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_government_forms_lock;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_forms_lock')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GenerateGovernmentFormsController::lockAction',));
                }
                not_hris_government_forms_lock:

                // hris_government_forms_lock_all
                if (0 === strpos($pathinfo, '/alphalist/lock/all') && preg_match('#^/alphalist/lock/all/(?P<type>[^/]++)/(?P<year>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_government_forms_lock_all;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_forms_lock_all')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GenerateGovernmentFormsController::lockAllAction',));
                }
                not_hris_government_forms_lock_all:

            }

            // hris_government_forms_year_list
            if (0 === strpos($pathinfo, '/alphalist/generate/governmentForms/yearlist') && preg_match('#^/alphalist/generate/governmentForms/yearlist/(?P<type>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_government_forms_year_list;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_forms_year_list')), array (  '_controller' => 'Hris\\AlphalistBundle\\Controller\\GenerateGovernmentFormsController::getYearListAction',));
            }
            not_hris_government_forms_year_list:

        }

        if (0 === strpos($pathinfo, '/etraining')) {
            // hris_etrain_index
            if ($pathinfo === '/etraining') {
                return array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\EtrainController::indexAction',  '_route' => 'hris_etrain_index',);
            }

            // hris_etrain_add_form
            if ($pathinfo === '/etraining/add') {
                return array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\EtrainController::addFormAction',  '_route' => 'hris_etrain_add_form',);
            }

        }

        if (0 === strpos($pathinfo, '/course')) {
            // hris_training_course_index
            if ($pathinfo === '/courses') {
                return array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\CourseController::indexAction',  '_route' => 'hris_training_course_index',);
            }

            // hris_training_course_add_form
            if ($pathinfo === '/course') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_training_course_add_form;
                }

                return array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\CourseController::addFormAction',  '_route' => 'hris_training_course_add_form',);
            }
            not_hris_training_course_add_form:

            // hris_training_course_add_submit
            if ($pathinfo === '/course') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_training_course_add_submit;
                }

                return array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\CourseController::addSubmitAction',  '_route' => 'hris_training_course_add_submit',);
            }
            not_hris_training_course_add_submit:

            // hris_training_course_edit_form
            if (preg_match('#^/course/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_training_course_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_course_edit_form')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\CourseController::editFormAction',));
            }
            not_hris_training_course_edit_form:

            // hris_training_course_edit_submit
            if (preg_match('#^/course/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_training_course_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_course_edit_submit')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\CourseController::editSubmitAction',));
            }
            not_hris_training_course_edit_submit:

            // hris_training_course_delete
            if (preg_match('#^/course/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_training_course_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_course_delete')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\CourseController::deleteAction',));
            }
            not_hris_training_course_delete:

            // hris_training_course_grid
            if ($pathinfo === '/courses/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_training_course_grid;
                }

                return array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\CourseController::gridAction',  '_route' => 'hris_training_course_grid',);
            }
            not_hris_training_course_grid:

        }

        if (0 === strpos($pathinfo, '/ajax/course')) {
            // hris_training_course_ajax_get_form
            if (preg_match('#^/ajax/course/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_training_course_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_course_ajax_get_form')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\CourseController::ajaxGetFormAction',));
            }
            not_hris_training_course_ajax_get_form:

            // hris_training_course_ajax_add
            if ($pathinfo === '/ajax/course') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_training_course_ajax_add;
                }

                return array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\CourseController::ajaxAddAction',  '_route' => 'hris_training_course_ajax_add',);
            }
            not_hris_training_course_ajax_add:

            // hris_training_course_ajax_save
            if (preg_match('#^/ajax/course/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_training_course_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_course_ajax_save')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\CourseController::ajaxSaveAction',));
            }
            not_hris_training_course_ajax_save:

        }

        if (0 === strpos($pathinfo, '/chapter')) {
            // hris_training_chapter_index
            if ($pathinfo === '/chapters') {
                return array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\ChapterController::indexAction',  '_route' => 'hris_training_chapter_index',);
            }

            // hris_training_chapter_add_form
            if (preg_match('#^/chapter/(?P<course_id>[^/]++)/add$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_training_chapter_add_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_chapter_add_form')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\ChapterController::addChapterFormAction',));
            }
            not_hris_training_chapter_add_form:

            // hris_training_chapter_add_submit
            if (preg_match('#^/chapter/(?P<course_id>[^/]++)/add$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_training_chapter_add_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_chapter_add_submit')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\ChapterController::addChapterSubmitAction',));
            }
            not_hris_training_chapter_add_submit:

            // hris_training_chapter_edit_form
            if (preg_match('#^/chapter/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_training_chapter_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_chapter_edit_form')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\ChapterController::editFormAction',));
            }
            not_hris_training_chapter_edit_form:

            // hris_training_chapter_edit_submit
            if (preg_match('#^/chapter/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_training_chapter_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_chapter_edit_submit')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\ChapterController::editSubmitAction',));
            }
            not_hris_training_chapter_edit_submit:

            // hris_training_chapter_delete
            if (preg_match('#^/chapter/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_training_chapter_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_chapter_delete')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\ChapterController::deleteChapterAction',));
            }
            not_hris_training_chapter_delete:

        }

        if (0 === strpos($pathinfo, '/section')) {
            // hris_training_section_index
            if ($pathinfo === '/sections') {
                return array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\MediaController::indexAction',  '_route' => 'hris_training_section_index',);
            }

            // hris_training_section_add_form
            if (preg_match('#^/section/(?P<chapter_id>[^/]++)/add$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_training_section_add_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_section_add_form')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\MediaController::addMediaFormAction',));
            }
            not_hris_training_section_add_form:

            // hris_training_section_add_submit
            if (preg_match('#^/section/(?P<chapter_id>[^/]++)/add$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_training_section_add_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_section_add_submit')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\MediaController::addMediaSubmitAction',));
            }
            not_hris_training_section_add_submit:

            // hris_training_section_edit_form
            if (preg_match('#^/section/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_training_section_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_section_edit_form')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\MediaController::editFormAction',));
            }
            not_hris_training_section_edit_form:

            // hris_training_section_edit_submit
            if (preg_match('#^/section/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_training_section_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_section_edit_submit')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\MediaController::editSubmitAction',));
            }
            not_hris_training_section_edit_submit:

            // hris_training_section_delete
            if (preg_match('#^/section/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_training_section_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_training_section_delete')), array (  '_controller' => 'Hris\\TrainingBundle\\Controller\\MediaController::deleteMediaAction',));
            }
            not_hris_training_section_delete:

        }

        if (0 === strpos($pathinfo, '/crm')) {
            if (0 === strpos($pathinfo, '/crm/account')) {
                // top_crm_account_index
                if ($pathinfo === '/crm/accounts') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_account_index;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::indexAction',  '_route' => 'top_crm_account_index',);
                }
                not_top_crm_account_index:

                // top_crm_account_add_form
                if ($pathinfo === '/crm/account') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_account_add_form;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::addFormAction',  '_route' => 'top_crm_account_add_form',);
                }
                not_top_crm_account_add_form:

                // top_crm_account_add_submit
                if ($pathinfo === '/crm/account') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_top_crm_account_add_submit;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::addSubmitAction',  '_route' => 'top_crm_account_add_submit',);
                }
                not_top_crm_account_add_submit:

                // top_crm_account_edit_form
                if (preg_match('#^/crm/account/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_account_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_account_edit_form')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::editFormAction',));
                }
                not_top_crm_account_edit_form:

                // top_crm_account_edit_submit
                if (preg_match('#^/crm/account/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_top_crm_account_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_account_edit_submit')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::editSubmitAction',));
                }
                not_top_crm_account_edit_submit:

                // top_crm_account_delete
                if (0 === strpos($pathinfo, '/crm/account/delete') && preg_match('#^/crm/account/delete/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_account_delete')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::deleteAction',));
                }

            }

            if (0 === strpos($pathinfo, '/crm/delete/ac')) {
                // top_crm_account_delete_location
                if ($pathinfo === '/crm/delete/acount/property/location') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_top_crm_account_delete_location;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::deleteLocationAction',  '_route' => 'top_crm_account_delete_location',);
                }
                not_top_crm_account_delete_location:

                // top_crm_account_delete_contact
                if ($pathinfo === '/crm/delete/account/contact') {
                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::deleteContactAction',  '_route' => 'top_crm_account_delete_contact',);
                }

            }

            if (0 === strpos($pathinfo, '/crm/a')) {
                if (0 === strpos($pathinfo, '/crm/accounts')) {
                    // top_crm_account_export
                    if ($pathinfo === '/crm/accounts/list/csv') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_top_crm_account_export;
                        }

                        return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::exportCSVAction',  '_route' => 'top_crm_account_export',);
                    }
                    not_top_crm_account_export:

                    // top_crm_account_grid
                    if ($pathinfo === '/crm/accounts/grid') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_top_crm_account_grid;
                        }

                        return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::gridAction',  '_route' => 'top_crm_account_grid',);
                    }
                    not_top_crm_account_grid:

                }

                // top_crm_account_ajax_get
                if (0 === strpos($pathinfo, '/crm/ajax/account') && preg_match('#^/crm/ajax/account/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_account_ajax_get;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_account_ajax_get')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::ajaxGetAction',));
                }
                not_top_crm_account_ajax_get:

            }

            // top_crm_account_search_account
            if ($pathinfo === '/crm/search/account') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_top_crm_account_search_account;
                }

                return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::searchAccountAction',  '_route' => 'top_crm_account_search_account',);
            }
            not_top_crm_account_search_account:

            if (0 === strpos($pathinfo, '/crm/get')) {
                if (0 === strpos($pathinfo, '/crm/get/service')) {
                    if (0 === strpos($pathinfo, '/crm/get/serviceo')) {
                        // top_crm_account_get_service_opportunity
                        if (0 === strpos($pathinfo, '/crm/get/serviceopportunity') && preg_match('#^/crm/get/serviceopportunity/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_account_get_service_opportunity')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::getServiceOpportunityAction',));
                        }

                        // top_crm_account_get_service_order
                        if (0 === strpos($pathinfo, '/crm/get/serviceorder') && preg_match('#^/crm/get/serviceorder/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_account_get_service_order')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::getServiceOrderAction',));
                        }

                    }

                    // top_crm_account_get_service_invoice
                    if (0 === strpos($pathinfo, '/crm/get/serviceinvoice') && preg_match('#^/crm/get/serviceinvoice/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_account_get_service_invoice')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::getServiceInvoiceAction',));
                    }

                }

                // top_crm_account_get_all_sched
                if (0 === strpos($pathinfo, '/crm/get/all/schedule') && preg_match('#^/crm/get/all/schedule/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_account_get_all_sched')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::getAllSchedAccountAction',));
                }

            }

            if (0 === strpos($pathinfo, '/crm/a')) {
                // top_crm_account
                if (0 === strpos($pathinfo, '/crm/ajax/accounts') && preg_match('#^/crm/ajax/accounts(?:/(?P<key>[^/]++))?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_account;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_account')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::getAccountListAction',  'key' => '',));
                }
                not_top_crm_account:

                // top_crm_account_get_location_ajax
                if (0 === strpos($pathinfo, '/crm/account/location') && preg_match('#^/crm/account/location/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_account_get_location_ajax;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_account_get_location_ajax')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::getAccountLocationAction',));
                }
                not_top_crm_account_get_location_ajax:

            }

            // top_crm_account_map_get_location
            if (0 === strpos($pathinfo, '/crm/get/latitude/longitude') && preg_match('#^/crm/get/latitude/longitude/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_account_map_get_location')), array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\AddressController::getLocationAction',));
            }

            if (0 === strpos($pathinfo, '/crm/account-location')) {
                // top_crm_account_ajax_location
                if ($pathinfo === '/crm/account-location') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_top_crm_account_ajax_location;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::ajaxLocationSubmitAction',  '_route' => 'top_crm_account_ajax_location',);
                }
                not_top_crm_account_ajax_location:

                // top_crm_customer_ajax_location_get
                if (preg_match('#^/crm/account\\-location/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_customer_ajax_location_get;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_customer_ajax_location_get')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CustomerController::ajaxLocationFormAction',));
                }
                not_top_crm_customer_ajax_location_get:

            }

            if (0 === strpos($pathinfo, '/crm/contact')) {
                // top_crm_contact_index
                if ($pathinfo === '/crm/contacts') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_contact_index;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ContactController::indexAction',  '_route' => 'top_crm_contact_index',);
                }
                not_top_crm_contact_index:

                // top_crm_contact_add_form
                if ($pathinfo === '/crm/contact') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_contact_add_form;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ContactController::addFormAction',  '_route' => 'top_crm_contact_add_form',);
                }
                not_top_crm_contact_add_form:

                // top_crm_contact_add_submit
                if ($pathinfo === '/crm/contact') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_top_crm_contact_add_submit;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ContactController::addSubmitAction',  '_route' => 'top_crm_contact_add_submit',);
                }
                not_top_crm_contact_add_submit:

                // top_crm_contact_edit_form
                if (preg_match('#^/crm/contact/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_contact_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_contact_edit_form')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ContactController::editFormAction',));
                }
                not_top_crm_contact_edit_form:

                // top_crm_contact_edit_submit
                if (preg_match('#^/crm/contact/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_top_crm_contact_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_contact_edit_submit')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ContactController::editSubmitAction',));
                }
                not_top_crm_contact_edit_submit:

                // top_crm_contact_delete
                if (0 === strpos($pathinfo, '/crm/contact/delete') && preg_match('#^/crm/contact/delete/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_contact_delete')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ContactController::deleteAction',));
                }

                // top_crm_contact_grid
                if ($pathinfo === '/crm/contacts/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_contact_grid;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ContactController::gridAction',  '_route' => 'top_crm_contact_grid',);
                }
                not_top_crm_contact_grid:

            }

            // top_crm_contact_get_subservice
            if (0 === strpos($pathinfo, '/crm/get/subservice') && preg_match('#^/crm/get/subservice/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_contact_get_subservice')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ContactController::getSubServiceAction',));
            }

            if (0 === strpos($pathinfo, '/crm/lead')) {
                // top_crm_lead_index
                if ($pathinfo === '/crm/leads') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_lead_index;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\LeadController::indexAction',  '_route' => 'top_crm_lead_index',);
                }
                not_top_crm_lead_index:

                // top_crm_lead_add_form
                if ($pathinfo === '/crm/lead') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_lead_add_form;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\LeadController::addFormAction',  '_route' => 'top_crm_lead_add_form',);
                }
                not_top_crm_lead_add_form:

                // top_crm_lead_add_submit
                if ($pathinfo === '/crm/lead') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_top_crm_lead_add_submit;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\LeadController::addSubmitAction',  '_route' => 'top_crm_lead_add_submit',);
                }
                not_top_crm_lead_add_submit:

                // top_crm_lead_edit_form
                if (preg_match('#^/crm/lead/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_lead_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_lead_edit_form')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\LeadController::editFormAction',));
                }
                not_top_crm_lead_edit_form:

                // top_crm_lead_edit_submit
                if (preg_match('#^/crm/lead/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_top_crm_lead_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_lead_edit_submit')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\LeadController::editSubmitAction',));
                }
                not_top_crm_lead_edit_submit:

                // top_crm_lead_delete
                if (0 === strpos($pathinfo, '/crm/lead/delete') && preg_match('#^/crm/lead/delete/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_lead_delete')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\LeadController::deleteAction',));
                }

                // top_crm_lead_grid
                if ($pathinfo === '/crm/leads/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_lead_grid;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\LeadController::gridAction',  '_route' => 'top_crm_lead_grid',);
                }
                not_top_crm_lead_grid:

            }

            // top_crm_lead_search_lead
            if ($pathinfo === '/crm/search/lead') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_top_crm_lead_search_lead;
                }

                return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\LeadController::searchLeadAction',  '_route' => 'top_crm_lead_search_lead',);
            }
            not_top_crm_lead_search_lead:

            // top_crm_lead_convert
            if (0 === strpos($pathinfo, '/crm/convert/lead') && preg_match('#^/crm/convert/lead/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_lead_convert')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\LeadController::convertAction',));
            }

            if (0 === strpos($pathinfo, '/crm/opportunit')) {
                // top_crm_opportunity_index
                if ($pathinfo === '/crm/opportunities') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_opportunity_index;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::indexAction',  '_route' => 'top_crm_opportunity_index',);
                }
                not_top_crm_opportunity_index:

                if (0 === strpos($pathinfo, '/crm/opportunity')) {
                    // top_crm_opportunity_add_form
                    if ($pathinfo === '/crm/opportunity') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_top_crm_opportunity_add_form;
                        }

                        return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::addFormAction',  '_route' => 'top_crm_opportunity_add_form',);
                    }
                    not_top_crm_opportunity_add_form:

                    // top_crm_opportunity_add_submit
                    if ($pathinfo === '/crm/opportunity') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_top_crm_opportunity_add_submit;
                        }

                        return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::addSubmitAction',  '_route' => 'top_crm_opportunity_add_submit',);
                    }
                    not_top_crm_opportunity_add_submit:

                    // top_crm_opportunity_edit_form
                    if (preg_match('#^/crm/opportunity/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_top_crm_opportunity_edit_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_opportunity_edit_form')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::editFormAction',));
                    }
                    not_top_crm_opportunity_edit_form:

                    // top_crm_opportunity_edit_submit
                    if (preg_match('#^/crm/opportunity/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_top_crm_opportunity_edit_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_opportunity_edit_submit')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::editSubmitAction',));
                    }
                    not_top_crm_opportunity_edit_submit:

                    // top_crm_opportunity_delete
                    if (0 === strpos($pathinfo, '/crm/opportunity/delete') && preg_match('#^/crm/opportunity/delete/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_opportunity_delete')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::deleteAction',));
                    }

                }

                if (0 === strpos($pathinfo, '/crm/opportunities')) {
                    // top_crm_opportunity_export
                    if ($pathinfo === '/crm/opportunities/list/csv') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_top_crm_opportunity_export;
                        }

                        return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::exportCSVAction',  '_route' => 'top_crm_opportunity_export',);
                    }
                    not_top_crm_opportunity_export:

                    // top_crm_opportunity_grid
                    if ($pathinfo === '/crm/opportunities/grid') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_top_crm_opportunity_grid;
                        }

                        return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::gridAction',  '_route' => 'top_crm_opportunity_grid',);
                    }
                    not_top_crm_opportunity_grid:

                }

                if (0 === strpos($pathinfo, '/crm/opportunity-location')) {
                    // top_crm_opportunity_ajax_location
                    if ($pathinfo === '/crm/opportunity-location') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_top_crm_opportunity_ajax_location;
                        }

                        return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::ajaxLocationSubmitAction',  '_route' => 'top_crm_opportunity_ajax_location',);
                    }
                    not_top_crm_opportunity_ajax_location:

                    // top_crm_opportunity_ajax_location_get
                    if (preg_match('#^/crm/opportunity\\-location/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_top_crm_opportunity_ajax_location_get;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_opportunity_ajax_location_get')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::ajaxLocationFormAction',));
                    }
                    not_top_crm_opportunity_ajax_location_get:

                }

            }

            if (0 === strpos($pathinfo, '/crm/delete/opportunity')) {
                // top_crm_opportunity_delete_location
                if ($pathinfo === '/crm/delete/opportunity/property/location') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_top_crm_opportunity_delete_location;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::deleteLocationAction',  '_route' => 'top_crm_opportunity_delete_location',);
                }
                not_top_crm_opportunity_delete_location:

                // top_crm_opportunity_delete_contact
                if ($pathinfo === '/crm/delete/opportunity/contact') {
                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::deleteContactAction',  '_route' => 'top_crm_opportunity_delete_contact',);
                }

            }

            // top_crm_opportunity
            if (0 === strpos($pathinfo, '/crm/ajax/opportunity') && preg_match('#^/crm/ajax/opportunity(?:/(?P<key>[^/]++))?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_top_crm_opportunity;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_opportunity')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::getOpportunityListAction',  'key' => '',));
            }
            not_top_crm_opportunity:

            if (0 === strpos($pathinfo, '/crm/opportunity-service')) {
                // top_crm_opportunity_service_won
                if (0 === strpos($pathinfo, '/crm/opportunity-service/won') && preg_match('#^/crm/opportunity\\-service/won/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_opportunity_service_won;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_opportunity_service_won')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::wonAction',));
                }
                not_top_crm_opportunity_service_won:

                // top_crm_opportunity_service_lost
                if (0 === strpos($pathinfo, '/crm/opportunity-service/lost') && preg_match('#^/crm/opportunity\\-service/lost/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_opportunity_service_lost;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_opportunity_service_lost')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::lostAction',));
                }
                not_top_crm_opportunity_service_lost:

            }

            // top_crm_opportunity_sublocation
            if ($pathinfo === '/crm/location/sublocation') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_top_crm_opportunity_sublocation;
                }

                return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::getSublocationAction',  '_route' => 'top_crm_opportunity_sublocation',);
            }
            not_top_crm_opportunity_sublocation:

            if (0 === strpos($pathinfo, '/crm/get')) {
                // top_crm_opportunity_get_service_opportunity
                if (0 === strpos($pathinfo, '/crm/get/opportunity/serviceopportunity') && preg_match('#^/crm/get/opportunity/serviceopportunity/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_opportunity_get_service_opportunity')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::getServiceOpportunityAction',));
                }

                // top_crm_opportunity_get_all_sched
                if (0 === strpos($pathinfo, '/crm/get/all/sched/opportunity') && preg_match('#^/crm/get/all/sched/opportunity/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_opportunity_get_all_sched')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\OpportunityController::getAllOpportunitySchedAction',));
                }

            }

            if (0 === strpos($pathinfo, '/crm/log')) {
                // top_crm_log_index
                if ($pathinfo === '/crm/logs') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_log_index;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CallLogController::indexAction',  '_route' => 'top_crm_log_index',);
                }
                not_top_crm_log_index:

                // top_crm_log_add_form
                if ($pathinfo === '/crm/log') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_log_add_form;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CallLogController::addFormAction',  '_route' => 'top_crm_log_add_form',);
                }
                not_top_crm_log_add_form:

                // top_crm_log_add_submit
                if ($pathinfo === '/crm/log') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_top_crm_log_add_submit;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CallLogController::addSubmitAction',  '_route' => 'top_crm_log_add_submit',);
                }
                not_top_crm_log_add_submit:

                // top_crm_log_edit_form
                if (preg_match('#^/crm/log/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_log_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_log_edit_form')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CallLogController::editFormAction',));
                }
                not_top_crm_log_edit_form:

                // top_crm_log_edit_submit
                if (preg_match('#^/crm/log/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_top_crm_log_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_log_edit_submit')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CallLogController::editSubmitAction',));
                }
                not_top_crm_log_edit_submit:

                // top_crm_log_delete
                if (0 === strpos($pathinfo, '/crm/log/delete') && preg_match('#^/crm/log/delete/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_crm_log_delete')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\callLogController::deleteAction',));
                }

                // top_crm_log_grid
                if ($pathinfo === '/crm/logs/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_log_grid;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CallLogController::gridAction',  '_route' => 'top_crm_log_grid',);
                }
                not_top_crm_log_grid:

            }

            if (0 === strpos($pathinfo, '/crm/dashboard')) {
                // dashboard_crm_index
                if ($pathinfo === '/crm/dashboard') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_dashboard_crm_index;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\DashboardController::indexAction',  '_route' => 'dashboard_crm_index',);
                }
                not_dashboard_crm_index:

                // dashboard_crm_add_form
                if ($pathinfo === '/crm/dashboard') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_dashboard_crm_add_form;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\DashboardController::addFormAction',  '_route' => 'dashboard_crm_add_form',);
                }
                not_dashboard_crm_add_form:

            }

            if (0 === strpos($pathinfo, '/crm/todo')) {
                if (0 === strpos($pathinfo, '/crm/todo/list')) {
                    // top_crm_todo_index
                    if ($pathinfo === '/crm/todo/lists') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_top_crm_todo_index;
                        }

                        return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\TodoController::indexAction',  '_route' => 'top_crm_todo_index',);
                    }
                    not_top_crm_todo_index:

                    // top_crm_todo_add_form
                    if ($pathinfo === '/crm/todo/list') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_top_crm_todo_add_form;
                        }

                        return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\TodoController::addFormAction',  '_route' => 'top_crm_todo_add_form',);
                    }
                    not_top_crm_todo_add_form:

                }

                // top_crm_todo_grid
                if ($pathinfo === '/crm/todo/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_top_crm_todo_grid;
                    }

                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\TodoController::gridAction',  '_route' => 'top_crm_todo_grid',);
                }
                not_top_crm_todo_grid:

            }

            // dashboard_sales_index
            if ($pathinfo === '/crm/sales/dashboard') {
                return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\SalesDashboardController::indexAction',  '_route' => 'dashboard_sales_index',);
            }

            // top_sales_expiring_contract_index
            if ($pathinfo === '/crm/expiring/contract') {
                return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ExpiringContractController::indexAction',  '_route' => 'top_sales_expiring_contract_index',);
            }

            if (0 === strpos($pathinfo, '/crm/s')) {
                // top_sales_save_new_contract_enddate
                if ($pathinfo === '/crm/save/contractend') {
                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ExpiringContractController::saveEndDateAction',  '_route' => 'top_sales_save_new_contract_enddate',);
                }

                // top_sales_search_about_expire_contract
                if (0 === strpos($pathinfo, '/crm/search/about/expired') && preg_match('#^/crm/search/about/expired/(?P<dfrom>[^/]++)/(?P<dto>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'top_sales_search_about_expire_contract')), array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ExpiringContractController::searchAboutExpireAction',));
                }

            }

            // top_sales_all_about_expire_contract
            if ($pathinfo === '/crm/all/about/expire') {
                return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\ExpiringContractController::allAboutExpireAction',  '_route' => 'top_sales_all_about_expire_contract',);
            }

            // top_sales_conversion_count
            if ($pathinfo === '/crm/conversion/count') {
                return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\SalesDashboardController::conversionCountAction',  '_route' => 'top_sales_conversion_count',);
            }

            if (0 === strpos($pathinfo, '/crm/sales')) {
                // top_sales_renewal_index
                if ($pathinfo === '/crm/sales/renewal') {
                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\RenewalController::indexAction',  '_route' => 'top_sales_renewal_index',);
                }

                // top_sales_renewal_close_service_order
                if ($pathinfo === '/crm/sales/close/service_order') {
                    return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\RenewalController::closeServiceOrderAction',  '_route' => 'top_sales_renewal_close_service_order',);
                }

            }

            // top_sales_deactivate_client
            if ($pathinfo === '/crm/deactivate/client') {
                return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\RenewalController::deactivateClientAction',  '_route' => 'top_sales_deactivate_client',);
            }

            // top_sales_reactivate_client
            if ($pathinfo === '/crm/reactivate/client') {
                return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\InactiveCustomerController::reactivateClientAction',  '_route' => 'top_sales_reactivate_client',);
            }

            // top_sales_inactive_client_index
            if ($pathinfo === '/crm/inactive/account/list') {
                return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\InactiveCustomerController::indexAction',  '_route' => 'top_sales_inactive_client_index',);
            }

            // quadrant_callcenter_calllog_evaluation
            if ($pathinfo === '/crm/agent/evaluation') {
                if (!in_array($this->context->getMethod(), array('POST', 'GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('POST', 'GET', 'HEAD'));
                    goto not_quadrant_callcenter_calllog_evaluation;
                }

                return array (  '_controller' => 'Quadrant\\CRMBundle\\Controller\\CallLogController::saveEvaluationAction',  '_route' => 'quadrant_callcenter_calllog_evaluation',);
            }
            not_quadrant_callcenter_calllog_evaluation:

        }

        if (0 === strpos($pathinfo, '/media/upload')) {
            // cat_media_upload_file
            if ($pathinfo === '/media/upload') {
                return array (  '_controller' => 'Quadrant\\MediaBundle\\Controller\\UploadController::uploadAction',  '_route' => 'cat_media_upload_file',);
            }

            if (0 === strpos($pathinfo, '/media/upload_')) {
                // cat_media_upload_doc
                if ($pathinfo === '/media/upload_doc') {
                    return array (  '_controller' => 'Quadrant\\MediaBundle\\Controller\\UploadController::uploadDocAction',  '_route' => 'cat_media_upload_doc',);
                }

                // cat_media_upload_video
                if ($pathinfo === '/media/upload_video') {
                    return array (  '_controller' => 'Quadrant\\MediaBundle\\Controller\\UploadController::uploadVideoAction',  '_route' => 'cat_media_upload_video',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/contact')) {
            // cnt_address_ajax_add
            if ($pathinfo === '/contact/address/ajax/add') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_cnt_address_ajax_add;
                }

                return array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\AddressController::ajaxAddAction',  '_route' => 'cnt_address_ajax_add',);
            }
            not_cnt_address_ajax_add:

            // cnt_phone_ajax_add
            if ($pathinfo === '/contact/phone/ajax/add') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_cnt_phone_ajax_add;
                }

                return array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\PhoneController::ajaxAddAction',  '_route' => 'cnt_phone_ajax_add',);
            }
            not_cnt_phone_ajax_add:

            // cnt_address_delete
            if (0 === strpos($pathinfo, '/contact/address/delete') && preg_match('#^/contact/address/delete/(?P<id>[^/]++)/(?P<account_id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_cnt_address_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'cnt_address_delete')), array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\AddressController::deleteAddressAction',));
            }
            not_cnt_address_delete:

            // cnt_phone_delete
            if (0 === strpos($pathinfo, '/contact/phone/delete') && preg_match('#^/contact/phone/delete/(?P<id>[^/]++)/(?P<supp_id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_cnt_phone_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'cnt_phone_delete')), array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\PhoneController::deletePhoneAction',));
            }
            not_cnt_phone_delete:

            // cnt_phone_contact_delete
            if (0 === strpos($pathinfo, '/contact/contact/phone/delete') && preg_match('#^/contact/contact/phone/delete/(?P<id>[^/]++)/(?P<contact_id>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'cnt_phone_contact_delete')), array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\PhoneController::deleteContactPhoneAction',));
            }

            if (0 === strpos($pathinfo, '/contact/phone_type')) {
                // cat_cnt_phone_type_index
                if ($pathinfo === '/contact/phone_types') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_cnt_phone_type_index;
                    }

                    return array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\PhoneTypeController::indexAction',  '_route' => 'cat_cnt_phone_type_index',);
                }
                not_cat_cnt_phone_type_index:

                // cat_cnt_phone_type_add_form
                if ($pathinfo === '/contact/phone_type') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_cnt_phone_type_add_form;
                    }

                    return array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\PhoneTypeController::addFormAction',  '_route' => 'cat_cnt_phone_type_add_form',);
                }
                not_cat_cnt_phone_type_add_form:

                // cat_cnt_phone_type_add_submit
                if ($pathinfo === '/contact/phone_type') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_cat_cnt_phone_type_add_submit;
                    }

                    return array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\PhoneTypeController::addSubmitAction',  '_route' => 'cat_cnt_phone_type_add_submit',);
                }
                not_cat_cnt_phone_type_add_submit:

                // cat_cnt_phone_type_edit_form
                if (preg_match('#^/contact/phone_type/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_cnt_phone_type_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_cnt_phone_type_edit_form')), array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\PhoneTypeController::editFormAction',));
                }
                not_cat_cnt_phone_type_edit_form:

                // cat_cnt_phone_type_edit_submit
                if (preg_match('#^/contact/phone_type/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_cat_cnt_phone_type_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_cnt_phone_type_edit_submit')), array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\PhoneTypeController::editSubmitAction',));
                }
                not_cat_cnt_phone_type_edit_submit:

                // cat_cnt_phone_type_delete
                if (0 === strpos($pathinfo, '/contact/phone_type/delete') && preg_match('#^/contact/phone_type/delete/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_cnt_phone_type_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_cnt_phone_type_delete')), array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\PhoneTypeController::deleteAction',));
                }
                not_cat_cnt_phone_type_delete:

                // cat_cnt_phone_type_grid
                if (rtrim($pathinfo, '/') === '/contact/phone_type/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_cnt_phone_type_grid;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'cat_cnt_phone_type_grid');
                    }

                    return array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\PhoneTypeController::gridAction',  '_route' => 'cat_cnt_phone_type_grid',);
                }
                not_cat_cnt_phone_type_grid:

            }

            if (0 === strpos($pathinfo, '/contact/contact')) {
                // cnt_contact_person_ajax_add
                if ($pathinfo === '/contact/contact/ajax/add') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_cnt_contact_person_ajax_add;
                    }

                    return array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\ContactPersonController::ajaxAddAction',  '_route' => 'cnt_contact_person_ajax_add',);
                }
                not_cnt_contact_person_ajax_add:

                // cnt_contact_person_delete
                if (0 === strpos($pathinfo, '/contact/contact/delete') && preg_match('#^/contact/contact/delete/(?P<id>[^/]++)/(?P<supp_id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cnt_contact_person_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'cnt_contact_person_delete')), array (  '_controller' => 'Quadrant\\ContactBundle\\Controller\\ContactPersonController::deleteContactPersonAction',));
                }
                not_cnt_contact_person_delete:

            }

        }

        if (0 === strpos($pathinfo, '/notifications')) {
            // cat_notification_get
            if ($pathinfo === '/notifications/all') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_cat_notification_get;
                }

                return array (  '_controller' => 'Quadrant\\NotificationBundle\\Controller\\NotificationController::notificationsAction',  '_route' => 'cat_notification_get',);
            }
            not_cat_notification_get:

            if (0 === strpos($pathinfo, '/notifications/read')) {
                // cat_notification_read
                if (preg_match('#^/notifications/read/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_notification_read;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_notification_read')), array (  '_controller' => 'Quadrant\\NotificationBundle\\Controller\\NotificationController::setReadAction',));
                }
                not_cat_notification_read:

                // cat_notification_read_all
                if ($pathinfo === '/notifications/readall') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_notification_read_all;
                    }

                    return array (  '_controller' => 'Quadrant\\NotificationBundle\\Controller\\NotificationController::setReadAllAction',  '_route' => 'cat_notification_read_all',);
                }
                not_cat_notification_read_all:

            }

            // cat_notification_index
            if (rtrim($pathinfo, '/') === '/notifications') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_cat_notification_index;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'cat_notification_index');
                }

                return array (  '_controller' => 'Quadrant\\NotificationBundle\\Controller\\NotificationController::indexAction',  '_route' => 'cat_notification_index',);
            }
            not_cat_notification_index:

            // cat_notification_grid
            if ($pathinfo === '/notifications/notifications/grid') {
                return array (  '_controller' => 'Quadrant\\NotificationBundle\\Controller\\NotificationController::gridnotificationsAction',  '_route' => 'cat_notification_grid',);
            }

        }

        if (0 === strpos($pathinfo, '/log')) {
            if (0 === strpos($pathinfo, '/login')) {
                // fos_user_security_login
                if ($pathinfo === '/login') {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_fos_user_security_login;
                    }

                    return array (  '_controller' => 'fos_user.security.controller:loginAction',  '_route' => 'fos_user_security_login',);
                }
                not_fos_user_security_login:

                // fos_user_security_check
                if ($pathinfo === '/login_check') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_fos_user_security_check;
                    }

                    return array (  '_controller' => 'fos_user.security.controller:checkAction',  '_route' => 'fos_user_security_check',);
                }
                not_fos_user_security_check:

            }

            // fos_user_security_logout
            if ($pathinfo === '/logout') {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_fos_user_security_logout;
                }

                return array (  '_controller' => 'fos_user.security.controller:logoutAction',  '_route' => 'fos_user_security_logout',);
            }
            not_fos_user_security_logout:

        }

        if (0 === strpos($pathinfo, '/configuration/config')) {
            // cat_config_index
            if ($pathinfo === '/configuration/config') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_cat_config_index;
                }

                return array (  '_controller' => 'Quadrant\\ConfigurationBundle\\Controller\\ConfigEntryController::indexAction',  '_route' => 'cat_config_index',);
            }
            not_cat_config_index:

            // cat_config_submit
            if ($pathinfo === '/configuration/config') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_cat_config_submit;
                }

                return array (  '_controller' => 'Quadrant\\ConfigurationBundle\\Controller\\ConfigEntryController::submitAction',  '_route' => 'cat_config_submit',);
            }
            not_cat_config_submit:

        }

        if (0 === strpos($pathinfo, '/user')) {
            if (0 === strpos($pathinfo, '/user/user')) {
                // cat_user_user_index
                if ($pathinfo === '/user/users') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_user_user_index;
                    }

                    return array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\UserController::indexAction',  '_route' => 'cat_user_user_index',);
                }
                not_cat_user_user_index:

                // cat_user_user_add_form
                if ($pathinfo === '/user/user') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_user_user_add_form;
                    }

                    return array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\UserController::addFormAction',  '_route' => 'cat_user_user_add_form',);
                }
                not_cat_user_user_add_form:

                // cat_user_user_add_submit
                if ($pathinfo === '/user/user') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_cat_user_user_add_submit;
                    }

                    return array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\UserController::addSubmitAction',  '_route' => 'cat_user_user_add_submit',);
                }
                not_cat_user_user_add_submit:

                // cat_user_user_edit_form
                if (preg_match('#^/user/user/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_user_user_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_user_user_edit_form')), array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\UserController::editFormAction',));
                }
                not_cat_user_user_edit_form:

                // cat_user_user_edit_submit
                if (preg_match('#^/user/user/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_cat_user_user_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_user_user_edit_submit')), array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\UserController::editSubmitAction',));
                }
                not_cat_user_user_edit_submit:

                // cat_user_user_delete
                if (preg_match('#^/user/user/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_user_user_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_user_user_delete')), array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\UserController::deleteAction',));
                }
                not_cat_user_user_delete:

                // cat_user_user_grid
                if ($pathinfo === '/user/users/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_user_user_grid;
                    }

                    return array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\UserController::gridAction',  '_route' => 'cat_user_user_grid',);
                }
                not_cat_user_user_grid:

                // cat_user_user_call_log_on
                if ($pathinfo === '/user/user/call_log/on') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_cat_user_user_call_log_on;
                    }

                    return array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\UserController::CallLogOnAction',  '_route' => 'cat_user_user_call_log_on',);
                }
                not_cat_user_user_call_log_on:

            }

            if (0 === strpos($pathinfo, '/user/group')) {
                // cat_user_group_index
                if ($pathinfo === '/user/groups') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_user_group_index;
                    }

                    return array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\GroupController::indexAction',  '_route' => 'cat_user_group_index',);
                }
                not_cat_user_group_index:

                // cat_user_group_add_form
                if ($pathinfo === '/user/group') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_user_group_add_form;
                    }

                    return array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\GroupController::addFormAction',  '_route' => 'cat_user_group_add_form',);
                }
                not_cat_user_group_add_form:

                // cat_user_group_add_submit
                if ($pathinfo === '/user/group') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_cat_user_group_add_submit;
                    }

                    return array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\GroupController::addSubmitAction',  '_route' => 'cat_user_group_add_submit',);
                }
                not_cat_user_group_add_submit:

                // cat_user_group_edit_form
                if (preg_match('#^/user/group/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_user_group_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_user_group_edit_form')), array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\GroupController::editFormAction',));
                }
                not_cat_user_group_edit_form:

                // cat_user_group_edit_submit
                if (preg_match('#^/user/group/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_cat_user_group_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_user_group_edit_submit')), array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\GroupController::editSubmitAction',));
                }
                not_cat_user_group_edit_submit:

                // cat_user_group_delete
                if (preg_match('#^/user/group/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_cat_user_group_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_user_group_delete')), array (  '_controller' => 'Quadrant\\UserBundle\\Controller\\GroupController::deleteAction',));
                }
                not_cat_user_group_delete:

            }

        }

        if (0 === strpos($pathinfo, '/location')) {
            // cat_location_city_by_region
            if (0 === strpos($pathinfo, '/location/location/cities') && preg_match('#^/location/location/cities/(?P<parent_id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_cat_location_city_by_region;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_location_city_by_region')), array (  '_controller' => 'Quadrant\\LocationBundle\\Controller\\LocationController::findCitiesByRegionAction',));
            }
            not_cat_location_city_by_region:

            // cat_location_city_get_state
            if (0 === strpos($pathinfo, '/location/get/state') && preg_match('#^/location/get/state/(?P<city_id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_cat_location_city_get_state;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_location_city_get_state')), array (  '_controller' => 'Quadrant\\LocationBundle\\Controller\\LocationController::findStateAction',));
            }
            not_cat_location_city_get_state:

            // cat_location_map
            if (0 === strpos($pathinfo, '/location/map') && preg_match('#^/location/map/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_cat_location_map;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'cat_location_map')), array (  '_controller' => 'Quadrant\\LocationBundle\\Controller\\LocationController::mapAction',));
            }
            not_cat_location_map:

        }

        if (0 === strpos($pathinfo, '/biometrics')) {
            if (0 === strpos($pathinfo, '/biometrics/biometrics')) {
                if (0 === strpos($pathinfo, '/biometrics/biometrics/index')) {
                    // hris_biometrics_attendance_index
                    if ($pathinfo === '/biometrics/biometrics/index') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_biometrics_attendance_index;
                        }

                        return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\DeviceDataController::indexAction',  '_route' => 'hris_biometrics_attendance_index',);
                    }
                    not_hris_biometrics_attendance_index:

                    // hris_biometrics_attendance_submit
                    if ($pathinfo === '/biometrics/biometrics/index') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_biometrics_attendance_submit;
                        }

                        return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\DeviceDataController::indexSubmitAction',  '_route' => 'hris_biometrics_attendance_submit',);
                    }
                    not_hris_biometrics_attendance_submit:

                }

                // hris_biometrics_attendance_ajax_grid
                if (0 === strpos($pathinfo, '/biometrics/biometrics/ajax') && preg_match('#^/biometrics/biometrics/ajax(?:/(?P<id>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_biometrics_attendance_ajax_grid')), array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\DeviceDataController::gridAttendancesAction',  'id' => NULL,  'date_from' => NULL,  'date_to' => NULL,));
                }

            }

            // hris_biometrics_attendance_auth
            if ($pathinfo === '/biometrics/login') {
                return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\BiometricsController::authTransmissionAction',  '_route' => 'hris_biometrics_attendance_auth',);
            }

            // hris_biometrics_attendance_trasmit
            if ($pathinfo === '/biometrics/transmit') {
                return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\BiometricsController::beginTransmissionAction',  '_route' => 'hris_biometrics_attendance_trasmit',);
            }

            if (0 === strpos($pathinfo, '/biometrics/p')) {
                // hris_biometrics_attendance_process
                if ($pathinfo === '/biometrics/process') {
                    return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\BiometricsController::processDataAction',  '_route' => 'hris_biometrics_attendance_process',);
                }

                // hris_biometrics_attendance_post_process
                if ($pathinfo === '/biometrics/postprocess') {
                    return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\BiometricsController::postProcessDataAction',  '_route' => 'hris_biometrics_attendance_post_process',);
                }

            }

            // hris_biometrics_attendance_receive
            if ($pathinfo === '/biometrics/receive') {
                return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\BiometricsController::receiveBiometricsDataAction',  '_route' => 'hris_biometrics_attendance_receive',);
            }

            // hris_biometrics_attendance_pair
            if ($pathinfo === '/biometrics/pair') {
                return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\BiometricsController::pairAttendanceRecordsAction',  '_route' => 'hris_biometrics_attendance_pair',);
            }

        }

        if (0 === strpos($pathinfo, '/cash')) {
            if (0 === strpos($pathinfo, '/cash/incentive')) {
                // hris_remuneration_incentive_index
                if ($pathinfo === '/cash/incentives') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_remuneration_incentive_index;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\IncentiveController::indexAction',  '_route' => 'hris_remuneration_incentive_index',);
                }
                not_hris_remuneration_incentive_index:

                // hris_remuneration_incentive_add_form
                if ($pathinfo === '/cash/incentive') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_remuneration_incentive_add_form;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\IncentiveController::addFormAction',  '_route' => 'hris_remuneration_incentive_add_form',);
                }
                not_hris_remuneration_incentive_add_form:

                // hris_remuneration_incentive_add_submit
                if ($pathinfo === '/cash/incentive') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_remuneration_incentive_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\IncentiveController::addSubmitAction',  '_route' => 'hris_remuneration_incentive_add_submit',);
                }
                not_hris_remuneration_incentive_add_submit:

                // hris_remuneration_incentive_edit_form
                if (preg_match('#^/cash/incentive/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_remuneration_incentive_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_incentive_edit_form')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\IncentiveController::editFormAction',));
                }
                not_hris_remuneration_incentive_edit_form:

                // hris_remuneration_incentive_edit_submit
                if (preg_match('#^/cash/incentive/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_remuneration_incentive_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_incentive_edit_submit')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\IncentiveController::editSubmitAction',));
                }
                not_hris_remuneration_incentive_edit_submit:

                // hris_remuneration_incentive_delete
                if (preg_match('#^/cash/incentive/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_remuneration_incentive_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_incentive_delete')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\IncentiveController::deleteAction',));
                }
                not_hris_remuneration_incentive_delete:

                // hris_remuneration_incentive_grid
                if ($pathinfo === '/cash/incentives/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_remuneration_incentive_grid;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\IncentiveController::gridAction',  '_route' => 'hris_remuneration_incentive_grid',);
                }
                not_hris_remuneration_incentive_grid:

            }

            if (0 === strpos($pathinfo, '/cash/ajax/incentives')) {
                // hris_remuneration_incentive_ajax_get
                if (preg_match('#^/cash/ajax/incentives/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_remuneration_incentive_ajax_get;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_incentive_ajax_get')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\IncentiveController::ajaxGetAction',));
                }
                not_hris_remuneration_incentive_ajax_get:

                // hris_remuneration_incentive_ajax_add
                if ($pathinfo === '/cash/ajax/incentives/add') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_remuneration_incentive_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\IncentiveController::ajaxAddAction',  '_route' => 'hris_remuneration_incentive_ajax_add',);
                }
                not_hris_remuneration_incentive_ajax_add:

            }

            if (0 === strpos($pathinfo, '/cash/incentive')) {
                // hris_remuneration_incentive_status
                if (preg_match('#^/cash/incentive/(?P<id>[^/]++)/status/(?P<status>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_remuneration_incentive_status;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_incentive_status')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\IncentiveController::statusUpdateAction',));
                }
                not_hris_remuneration_incentive_status:

                // hris_remuneration_incentive_ajax_grid
                if (0 === strpos($pathinfo, '/cash/incentives/ajax') && preg_match('#^/cash/incentives/ajax/(?P<id>[^/]++)/(?P<date_from>[^/]++)/(?P<date_to>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_incentive_ajax_grid')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\IncentiveController::gridIncentiveAction',));
                }

            }

            if (0 === strpos($pathinfo, '/cash/savings')) {
                // hris_remuneration_cashbond_index
                if ($pathinfo === '/cash/savingslist') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_remuneration_cashbond_index;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CashbondController::indexAction',  '_route' => 'hris_remuneration_cashbond_index',);
                }
                not_hris_remuneration_cashbond_index:

                // hris_remuneration_cashbond_edit_form
                if (preg_match('#^/cash/savings/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_remuneration_cashbond_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_cashbond_edit_form')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CashbondController::editFormAction',));
                }
                not_hris_remuneration_cashbond_edit_form:

                // hris_remuneration_cashbond_edit_submit
                if (preg_match('#^/cash/savings/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_remuneration_cashbond_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_cashbond_edit_submit')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CashbondController::editSubmitAction',));
                }
                not_hris_remuneration_cashbond_edit_submit:

                // hris_remuneration_cashbond_delete
                if (preg_match('#^/cash/savings/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_remuneration_cashbond_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_cashbond_delete')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CashbondController::deleteAction',));
                }
                not_hris_remuneration_cashbond_delete:

                // hris_remuneration_cashbond_grid
                if ($pathinfo === '/cash/savingslist/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_remuneration_cashbond_grid;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CashbondController::gridAction',  '_route' => 'hris_remuneration_cashbond_grid',);
                }
                not_hris_remuneration_cashbond_grid:

                if (0 === strpos($pathinfo, '/cash/savings-')) {
                    if (0 === strpos($pathinfo, '/cash/savings-loan')) {
                        // hris_remuneration_cashbond_add_loan_form
                        if (preg_match('#^/cash/savings\\-loan/(?P<emp_id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_remuneration_cashbond_add_loan_form;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_cashbond_add_loan_form')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CashbondController::addLoanAction',));
                        }
                        not_hris_remuneration_cashbond_add_loan_form:

                        // hris_remuneration_cashbond_add_loan_submit
                        if (preg_match('#^/cash/savings\\-loan/(?P<emp_id>[^/]++)$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_remuneration_cashbond_add_loan_submit;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_cashbond_add_loan_submit')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CashbondController::addLoanSubmitAction',));
                        }
                        not_hris_remuneration_cashbond_add_loan_submit:

                        if (0 === strpos($pathinfo, '/cash/savings-loan-view')) {
                            // hris_remuneration_cashbond_edit_loan_form
                            if (preg_match('#^/cash/savings\\-loan\\-view/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_remuneration_cashbond_edit_loan_form;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_cashbond_edit_loan_form')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CashbondController::editLoanAction',));
                            }
                            not_hris_remuneration_cashbond_edit_loan_form:

                            // hris_remuneration_cashbond_edit_loan_submit
                            if (preg_match('#^/cash/savings\\-loan\\-view/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_hris_remuneration_cashbond_edit_loan_submit;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_remuneration_cashbond_edit_loan_submit')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CashbondController::editLoanSubmitAction',));
                            }
                            not_hris_remuneration_cashbond_edit_loan_submit:

                        }

                    }

                    // hris_remuneration_cashbond_employee_ajax
                    if ($pathinfo === '/cash/savings-employee') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_remuneration_cashbond_employee_ajax;
                        }

                        return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CashbondController::getCashbondByEmployeeAjaxAction',  '_route' => 'hris_remuneration_cashbond_employee_ajax',);
                    }
                    not_hris_remuneration_cashbond_employee_ajax:

                }

            }

            if (0 === strpos($pathinfo, '/cash/loan/setting/type')) {
                // hris_loan_type_index
                if ($pathinfo === '/cash/loan/setting/types') {
                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanTypeController::indexAction',  '_route' => 'hris_loan_type_index',);
                }

                // hris_loan_type_add_form
                if ($pathinfo === '/cash/loan/setting/type') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_loan_type_add_form;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanTypeController::addFormAction',  '_route' => 'hris_loan_type_add_form',);
                }
                not_hris_loan_type_add_form:

                // hris_loan_type_add_submit
                if ($pathinfo === '/cash/loan/setting/type') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_loan_type_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanTypeController::addSubmitAction',  '_route' => 'hris_loan_type_add_submit',);
                }
                not_hris_loan_type_add_submit:

                // hris_loan_type_edit_form
                if (preg_match('#^/cash/loan/setting/type/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_loan_type_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_loan_type_edit_form')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanTypeController::editFormAction',));
                }
                not_hris_loan_type_edit_form:

                // hris_loan_type_edit_submit
                if (preg_match('#^/cash/loan/setting/type/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_loan_type_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_loan_type_edit_submit')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanTypeController::editSubmitAction',));
                }
                not_hris_loan_type_edit_submit:

                // hris_loan_type_delete
                if (preg_match('#^/cash/loan/setting/type/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_loan_type_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_loan_type_delete')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanTypeController::deleteAction',));
                }
                not_hris_loan_type_delete:

                // hris_loan_type_grid
                if ($pathinfo === '/cash/loan/setting/types/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_loan_type_grid;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanTypeController::gridAction',  '_route' => 'hris_loan_type_grid',);
                }
                not_hris_loan_type_grid:

            }

            // hris_loan_type_ajax_get
            if (0 === strpos($pathinfo, '/cash/ajax/loan/setting/type') && preg_match('#^/cash/ajax/loan/setting/type/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_loan_type_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_loan_type_ajax_get')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanTypeController::ajaxGetAction',));
            }
            not_hris_loan_type_ajax_get:

            if (0 === strpos($pathinfo, '/cash/loan/request')) {
                // hris_loan_request_index
                if ($pathinfo === '/cash/loan/requests') {
                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanRequestAdminController::indexAction',  '_route' => 'hris_loan_request_index',);
                }

                // hris_loan_request_add_form
                if ($pathinfo === '/cash/loan/request') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_loan_request_add_form;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanRequestAdminController::addFormAction',  '_route' => 'hris_loan_request_add_form',);
                }
                not_hris_loan_request_add_form:

                // hris_loan_request_add_submit
                if ($pathinfo === '/cash/loan/request/type') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_loan_request_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanRequestAdminController::addSubmitAction',  '_route' => 'hris_loan_request_add_submit',);
                }
                not_hris_loan_request_add_submit:

                // hris_loan_request_edit_form
                if (preg_match('#^/cash/loan/request/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_loan_request_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_loan_request_edit_form')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanRequestAdminController::editFormAction',));
                }
                not_hris_loan_request_edit_form:

                // hris_loan_request_end_loan_form
                if (preg_match('#^/cash/loan/request/(?P<id>[^/]++)/endloan$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_loan_request_end_loan_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_loan_request_end_loan_form')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanRequestAdminController::endLoanFormAction',));
                }
                not_hris_loan_request_end_loan_form:

                // hris_loan_request_end_loan_submit
                if (preg_match('#^/cash/loan/request/(?P<id>[^/]++)/endloan$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_loan_request_end_loan_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_loan_request_end_loan_submit')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanRequestAdminController::endLoanSubmitAction',));
                }
                not_hris_loan_request_end_loan_submit:

                // hris_loan_request_edit_submit
                if (preg_match('#^/cash/loan/request/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_loan_request_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_loan_request_edit_submit')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanRequestAdminController::editSubmitAction',));
                }
                not_hris_loan_request_edit_submit:

                // hris_loan_request_delete
                if (preg_match('#^/cash/loan/request/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_loan_request_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_loan_request_delete')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanRequestAdminController::deleteAction',));
                }
                not_hris_loan_request_delete:

                // hris_loan_request_grid
                if ($pathinfo === '/cash/loan/requests/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_loan_request_grid;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanRequestAdminController::gridAction',  '_route' => 'hris_loan_request_grid',);
                }
                not_hris_loan_request_grid:

            }

            // hris_loan_request_ajax_get
            if (0 === strpos($pathinfo, '/cash/ajax/loan/request') && preg_match('#^/cash/ajax/loan/request/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_loan_request_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_loan_request_ajax_get')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LoanRequestAdminController::ajaxGetAction',));
            }
            not_hris_loan_request_ajax_get:

            if (0 === strpos($pathinfo, '/cash/settings/cash_advance')) {
                // hris_advance_settings_index
                if ($pathinfo === '/cash/settings/cash_advance') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_advance_settings_index;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\RemunerationSettingsController::cashAdvanceSettingsIndexAction',  '_route' => 'hris_advance_settings_index',);
                }
                not_hris_advance_settings_index:

                // hris_advance_settings_submit
                if ($pathinfo === '/cash/settings/cash_advance') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_advance_settings_submit;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\RemunerationSettingsController::cashAdvanceSettingsSubmitAction',  '_route' => 'hris_advance_settings_submit',);
                }
                not_hris_advance_settings_submit:

            }

            if (0 === strpos($pathinfo, '/cash/c')) {
                if (0 === strpos($pathinfo, '/cash/commission')) {
                    // hris_commission_index
                    if ($pathinfo === '/cash/commissions') {
                        return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CommissionController::indexAction',  '_route' => 'hris_commission_index',);
                    }

                    // hris_commission_add_form
                    if ($pathinfo === '/cash/commission') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_commission_add_form;
                        }

                        return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CommissionController::addFormAction',  '_route' => 'hris_commission_add_form',);
                    }
                    not_hris_commission_add_form:

                    // hris_commission_add_submit
                    if ($pathinfo === '/cash/commission') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_commission_add_submit;
                        }

                        return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CommissionController::addSubmitAction',  '_route' => 'hris_commission_add_submit',);
                    }
                    not_hris_commission_add_submit:

                    // hris_commission_edit_form
                    if (preg_match('#^/cash/commission/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_commission_edit_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_commission_edit_form')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CommissionController::editFormAction',));
                    }
                    not_hris_commission_edit_form:

                    // hris_commission_edit_submit
                    if (preg_match('#^/cash/commission/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_commission_edit_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_commission_edit_submit')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CommissionController::editSubmitAction',));
                    }
                    not_hris_commission_edit_submit:

                    // hris_commission_delete
                    if (preg_match('#^/cash/commission/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_commission_delete;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_commission_delete')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CommissionController::deleteAction',));
                    }
                    not_hris_commission_delete:

                    // hris_commission_grid
                    if ($pathinfo === '/cash/commissions/grid') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_commission_grid;
                        }

                        return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CommissionController::gridAction',  '_route' => 'hris_commission_grid',);
                    }
                    not_hris_commission_grid:

                    // hris_commission_print
                    if (0 === strpos($pathinfo, '/cash/commission/print') && preg_match('#^/cash/commission/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_commission_print;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_commission_print')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CommissionController::printCommissionAction',));
                    }
                    not_hris_commission_print:

                }

                if (0 === strpos($pathinfo, '/cash/cash_advance')) {
                    // hris_petty_cash_index
                    if ($pathinfo === '/cash/cash_advance') {
                        return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::indexAction',  '_route' => 'hris_petty_cash_index',);
                    }

                    if (0 === strpos($pathinfo, '/cash/cash_advance/add')) {
                        // hris_petty_cash_add_form
                        if (preg_match('#^/cash/cash_advance/add(?:/(?P<emp>[^/]++))?$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_petty_cash_add_form;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_petty_cash_add_form')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::addFormAction',  'emp' => NULL,));
                        }
                        not_hris_petty_cash_add_form:

                        // hris_petty_cash_add_submit
                        if (preg_match('#^/cash/cash_advance/add(?:/(?P<emp>[^/]++))?$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_petty_cash_add_submit;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_petty_cash_add_submit')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::addSubmitAction',  'emp' => NULL,));
                        }
                        not_hris_petty_cash_add_submit:

                    }

                    // hris_petty_cash_edit_form
                    if (preg_match('#^/cash/cash_advance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_petty_cash_edit_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_petty_cash_edit_form')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::editFormAction',));
                    }
                    not_hris_petty_cash_edit_form:

                    // hris_petty_cash_edit_submit
                    if (preg_match('#^/cash/cash_advance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_petty_cash_edit_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_petty_cash_edit_submit')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::editSubmitAction',));
                    }
                    not_hris_petty_cash_edit_submit:

                    // hris_petty_cash_delete
                    if (preg_match('#^/cash/cash_advance/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_petty_cash_delete;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_petty_cash_delete')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::deleteAction',));
                    }
                    not_hris_petty_cash_delete:

                }

            }

            // hris_petty_cash_grid
            if ($pathinfo === '/cash/petty_cash/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_petty_cash_grid;
                }

                return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::gridAction',  '_route' => 'hris_petty_cash_grid',);
            }
            not_hris_petty_cash_grid:

            if (0 === strpos($pathinfo, '/cash/ajax/petty_cash')) {
                // hris_petty_cash_ajax_get
                if (preg_match('#^/cash/ajax/petty_cash/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_petty_cash_ajax_get;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_petty_cash_ajax_get')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::ajaxGetAction',));
                }
                not_hris_petty_cash_ajax_get:

                // hris_petty_cash_ajax_add
                if ($pathinfo === '/cash/ajax/petty_cash/add') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_petty_cash_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::ajaxAddAction',  '_route' => 'hris_petty_cash_ajax_add',);
                }
                not_hris_petty_cash_ajax_add:

            }

            // hris_petty_cash_status
            if (0 === strpos($pathinfo, '/cash/cash_advance') && preg_match('#^/cash/cash_advance/(?P<id>[^/]++)/status/(?P<status>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_petty_cash_status;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_petty_cash_status')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::statusUpdateAction',));
            }
            not_hris_petty_cash_status:

            // hris_petty_cash_ajax_grid
            if (0 === strpos($pathinfo, '/cash/petty_cash/ajax') && preg_match('#^/cash/petty_cash/ajax/(?P<id>[^/]++)/(?P<date_from>[^/]++)/(?P<date_to>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_petty_cash_ajax_grid')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::gridPettyCashAction',));
            }

            // hris_petty_cash_print
            if (0 === strpos($pathinfo, '/cash/cash_advance') && preg_match('#^/cash/cash_advance/(?P<id>[^/]++)/print/?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_petty_cash_print;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'hris_petty_cash_print');
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_petty_cash_print')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\PettyCashController::printPettyCashAction',));
            }
            not_hris_petty_cash_print:

            if (0 === strpos($pathinfo, '/cash/disbursement')) {
                // hris_liquidation_index
                if ($pathinfo === '/cash/disbursement') {
                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LiquidationController::indexAction',  '_route' => 'hris_liquidation_index',);
                }

                if (0 === strpos($pathinfo, '/cash/disbursement/view')) {
                    // hris_liquidation_view_form
                    if (preg_match('#^/cash/disbursement/view/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_liquidation_view_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_liquidation_view_form')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LiquidationController::viewFormAction',));
                    }
                    not_hris_liquidation_view_form:

                    // hris_liquidation_view_submit
                    if (preg_match('#^/cash/disbursement/view/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_liquidation_view_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_liquidation_view_submit')), array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LiquidationController::viewSubmitAction',));
                    }
                    not_hris_liquidation_view_submit:

                }

                // hris_liquidation_grid
                if ($pathinfo === '/cash/disbursements/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_liquidation_grid;
                    }

                    return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\LiquidationController::gridAction',  '_route' => 'hris_liquidation_grid',);
                }
                not_hris_liquidation_grid:

            }

            // hris_compute_tax
            if ($pathinfo === '/cash/commission/tax/hris_compute_tax') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_compute_tax;
                }

                return array (  '_controller' => 'Hris\\RemunerationBundle\\Controller\\CommissionController::computeTaxAction',  '_route' => 'hris_compute_tax',);
            }
            not_hris_compute_tax:

        }

        if (0 === strpos($pathinfo, '/workforce')) {
            if (0 === strpos($pathinfo, '/workforce/employee')) {
                // hris_workforce_get_cities
                if (0 === strpos($pathinfo, '/workforce/employee/cities') && preg_match('#^/workforce/employee/cities/(?P<parent_id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_get_cities;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_get_cities')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::getChildLocationAction',));
                }
                not_hris_workforce_get_cities:

                // hris_workforce_get_states
                if (0 === strpos($pathinfo, '/workforce/employee/states') && preg_match('#^/workforce/employee/states/(?P<parent_id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_get_states;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_get_states')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::getChildLocationAction',));
                }
                not_hris_workforce_get_states:

                // hris_workforce_employee_index
                if ($pathinfo === '/workforce/employees') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_employee_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::indexAction',  '_route' => 'hris_workforce_employee_index',);
                }
                not_hris_workforce_employee_index:

                // hris_workforce_employee_add_form
                if ($pathinfo === '/workforce/employee') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_employee_add_form;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::addFormAction',  '_route' => 'hris_workforce_employee_add_form',);
                }
                not_hris_workforce_employee_add_form:

                // hris_workforce_employee_add_submit
                if ($pathinfo === '/workforce/employee') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_employee_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::addSubmitAction',  '_route' => 'hris_workforce_employee_add_submit',);
                }
                not_hris_workforce_employee_add_submit:

                // hris_workforce_employee_edit_form
                if (preg_match('#^/workforce/employee/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_employee_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_employee_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::editFormAction',));
                }
                not_hris_workforce_employee_edit_form:

                // hris_workforce_employee_edit_submit
                if (preg_match('#^/workforce/employee/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_employee_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_employee_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::editSubmitAction',));
                }
                not_hris_workforce_employee_edit_submit:

                // hris_workforce_employee_grid
                if ($pathinfo === '/workforce/employees/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_employee_grid;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::gridAction',  '_route' => 'hris_workforce_employee_grid',);
                }
                not_hris_workforce_employee_grid:

                // hris_workforce_employee_201_print
                if (0 === strpos($pathinfo, '/workforce/employee/201file') && preg_match('#^/workforce/employee/201file/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_employee_201_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_employee_201_print')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::print201Action',));
                }
                not_hris_workforce_employee_201_print:

                // hris_workforce_employee_print
                if (0 === strpos($pathinfo, '/workforce/employee/print') && preg_match('#^/workforce/employee/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_employee_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_employee_print')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::printEmpInfoAction',));
                }
                not_hris_workforce_employee_print:

                if (0 === strpos($pathinfo, '/workforce/employees')) {
                    // hris_workforce_employee_ajax_get
                    if ($pathinfo === '/workforce/employees/ajax') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_employee_ajax_get;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::ajaxFilterEmployeeAction',  '_route' => 'hris_workforce_employee_ajax_get',);
                    }
                    not_hris_workforce_employee_ajax_get:

                    // hris_workforce_employeeId_ajax_get
                    if ($pathinfo === '/workforce/employees/empId/ajax') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_employeeId_ajax_get;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::ajaxFilterEmployeeIdAction',  '_route' => 'hris_workforce_employeeId_ajax_get',);
                    }
                    not_hris_workforce_employeeId_ajax_get:

                }

                // hris_admin_employee_ajax_get_details
                if (0 === strpos($pathinfo, '/workforce/employee/detail') && preg_match('#^/workforce/employee/detail/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_employee_ajax_get_details')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::ajaxGetEmployeeDetailsAction',));
                }

                // hris_workforce_emplyoee_total
                if (0 === strpos($pathinfo, '/workforce/employees/update') && preg_match('#^/workforce/employees/update(?:/(?P<id>[^/]++)(?:/(?P<month>[^/]++)(?:/(?P<year>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_emplyoee_total')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::updateAttendanceAction',  'id' => NULL,  'month' => NULL,  'year' => NULL,));
                }

                // hris_workforce_employee_email
                if ($pathinfo === '/workforce/employee/email/view') {
                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::viewEmailTemplateAction',  '_route' => 'hris_workforce_employee_email',);
                }

                // hris_workforce_employee_resend_email
                if (preg_match('#^/workforce/employee/(?P<id>[^/]++)/email/resend$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_employee_resend_email')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::resendDetailsAction',));
                }

                if (0 === strpos($pathinfo, '/workforce/employee/guarantor')) {
                    // hris_workforce_employee_guarantor_delete
                    if (preg_match('#^/workforce/employee/guarantor/(?P<id>[^/]++)/(?P<guarantor_id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_employee_guarantor_delete;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_employee_guarantor_delete')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::deleteGuarantorAction',));
                    }
                    not_hris_workforce_employee_guarantor_delete:

                    // hris_workforce_employee_guarantor_entry
                    if (0 === strpos($pathinfo, '/workforce/employee/guarantor/edit') && preg_match('#^/workforce/employee/guarantor/edit/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_employee_guarantor_entry;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_employee_guarantor_entry')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::ajaxGetGuarantorAction',));
                    }
                    not_hris_workforce_employee_guarantor_entry:

                }

            }

            if (0 === strpos($pathinfo, '/workforce/a')) {
                // hris_attendance_emp-attendance_import
                if ($pathinfo === '/workforce/attendances/import') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_attendance_empattendance_import;
                    }

                    return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::importDTRAction',  '_route' => 'hris_attendance_emp-attendance_import',);
                }
                not_hris_attendance_empattendance_import:

                if (0 === strpos($pathinfo, '/workforce/appraisal')) {
                    // hris_workforce_appraisals_index
                    if ($pathinfo === '/workforce/appraisal') {
                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AppraisalController::indexAction',  '_route' => 'hris_workforce_appraisals_index',);
                    }

                    if (0 === strpos($pathinfo, '/workforce/appraisals')) {
                        if (0 === strpos($pathinfo, '/workforce/appraisals/add')) {
                            // hris_workforce_appraisals_add_form
                            if ($pathinfo === '/workforce/appraisals/add') {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_workforce_appraisals_add_form;
                                }

                                return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AppraisalController::addFormAction',  '_route' => 'hris_workforce_appraisals_add_form',);
                            }
                            not_hris_workforce_appraisals_add_form:

                            // hris_workforce_appraisals_add_submit
                            if ($pathinfo === '/workforce/appraisals/add') {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_hris_workforce_appraisals_add_submit;
                                }

                                return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AppraisalController::addSubmitAction',  '_route' => 'hris_workforce_appraisals_add_submit',);
                            }
                            not_hris_workforce_appraisals_add_submit:

                        }

                        // hris_workforce_get_evaluator
                        if (0 === strpos($pathinfo, '/workforce/appraisals/evaluators') && preg_match('#^/workforce/appraisals/evaluators/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_workforce_get_evaluator;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_get_evaluator')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AppraisalController::getEvalListAction',));
                        }
                        not_hris_workforce_get_evaluator:

                        // hris_workforce_appraisals_edit_form
                        if (preg_match('#^/workforce/appraisals/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_workforce_appraisals_edit_form;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_appraisals_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AppraisalController::editFormAction',));
                        }
                        not_hris_workforce_appraisals_edit_form:

                        if (0 === strpos($pathinfo, '/workforce/appraisals/evaluate')) {
                            // hris_workforce_appraisals_evaluate
                            if (preg_match('#^/workforce/appraisals/evaluate/(?P<eval>[^/]++)$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_workforce_appraisals_evaluate;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_appraisals_evaluate')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AppraisalController::evaluateAction',));
                            }
                            not_hris_workforce_appraisals_evaluate:

                            // hris_workforce_appraisals_evaluate_submit
                            if (preg_match('#^/workforce/appraisals/evaluate/(?P<eval>[^/]++)$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_hris_workforce_appraisals_evaluate_submit;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_appraisals_evaluate_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AppraisalController::evaluateSubmitAction',));
                            }
                            not_hris_workforce_appraisals_evaluate_submit:

                        }

                    }

                    // hris_workforce_appraisals_grid
                    if ($pathinfo === '/workforce/appraisal/grid') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_appraisals_grid;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AppraisalController::gridAction',  '_route' => 'hris_workforce_appraisals_grid',);
                    }
                    not_hris_workforce_appraisals_grid:

                    // hris_workforce_appraisals_delete
                    if (preg_match('#^/workforce/appraisal/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_appraisals_delete;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_appraisals_delete')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AppraisalController::deleteAction',));
                    }
                    not_hris_workforce_appraisals_delete:

                    // hris_workforce_appraisals_evaluate_print
                    if (0 === strpos($pathinfo, '/workforce/appraisals/print') && preg_match('#^/workforce/appraisals/print/(?P<eval>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_appraisals_evaluate_print;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_appraisals_evaluate_print')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AppraisalController::evaluatePrintAction',));
                    }
                    not_hris_workforce_appraisals_evaluate_print:

                }

            }

            // hris_workforce_benefits_index
            if ($pathinfo === '/workforce/benefits') {
                return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\BenefitController::indexAction',  '_route' => 'hris_workforce_benefits_index',);
            }

            // hris_workforce_benefits_add_form
            if ($pathinfo === '/workforce/addbenefit') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_workforce_benefits_add_form;
                }

                return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\BenefitController::addFormAction',  '_route' => 'hris_workforce_benefits_add_form',);
            }
            not_hris_workforce_benefits_add_form:

            // hris_workforce_benefits_edit_form
            if ($pathinfo === '/workforce/benefit') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_workforce_benefits_edit_form;
                }

                return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\BenefitController::addFormAction',  '_route' => 'hris_workforce_benefits_edit_form',);
            }
            not_hris_workforce_benefits_edit_form:

            if (0 === strpos($pathinfo, '/workforce/propert')) {
                // hris_workforce_issued_property_index
                if ($pathinfo === '/workforce/properties') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_issued_property_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::indexAction',  '_route' => 'hris_workforce_issued_property_index',);
                }
                not_hris_workforce_issued_property_index:

                if (0 === strpos($pathinfo, '/workforce/property')) {
                    // hris_workforce_get_emp
                    if (0 === strpos($pathinfo, '/workforce/property/employees') && preg_match('#^/workforce/property/employees/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_get_emp;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_get_emp')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::getEmpListAction',));
                    }
                    not_hris_workforce_get_emp:

                    // hris_workforce_issued_property_add_form
                    if ($pathinfo === '/workforce/property') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_issued_property_add_form;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::addFormAction',  '_route' => 'hris_workforce_issued_property_add_form',);
                    }
                    not_hris_workforce_issued_property_add_form:

                    // hris_workforce_issued_property_add_submit
                    if ($pathinfo === '/workforce/property') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_workforce_issued_property_add_submit;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::addSubmitAction',  '_route' => 'hris_workforce_issued_property_add_submit',);
                    }
                    not_hris_workforce_issued_property_add_submit:

                    // hris_workforce_issued_property_edit_form
                    if (preg_match('#^/workforce/property/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_issued_property_edit_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_issued_property_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::editFormAction',));
                    }
                    not_hris_workforce_issued_property_edit_form:

                    // hris_workforce_issued_property_edit_submit
                    if (preg_match('#^/workforce/property/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_workforce_issued_property_edit_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_issued_property_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::editSubmitAction',));
                    }
                    not_hris_workforce_issued_property_edit_submit:

                }

                if (0 === strpos($pathinfo, '/workforce/properties')) {
                    if (0 === strpos($pathinfo, '/workforce/properties/print')) {
                        // hris_workforce_issued_properties_print
                        if ($pathinfo === '/workforce/properties/print') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_workforce_issued_properties_print;
                            }

                            return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::printIssuedAction',  '_route' => 'hris_workforce_issued_properties_print',);
                        }
                        not_hris_workforce_issued_properties_print:

                        // hris_workforce_issued_property_print
                        if (preg_match('#^/workforce/properties/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_workforce_issued_property_print;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_issued_property_print')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::printPropertyAction',));
                        }
                        not_hris_workforce_issued_property_print:

                    }

                    // hris_workforce_issued_property_grid
                    if ($pathinfo === '/workforce/properties/grid') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_issued_property_grid;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::gridAction',  '_route' => 'hris_workforce_issued_property_grid',);
                    }
                    not_hris_workforce_issued_property_grid:

                }

                // hris_workforce_issued_property_delete
                if (0 === strpos($pathinfo, '/workforce/property') && preg_match('#^/workforce/property/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_issued_property_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_issued_property_delete')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::deleteAction',));
                }
                not_hris_workforce_issued_property_delete:

                if (0 === strpos($pathinfo, '/workforce/properties/ajax')) {
                    // hris_workforce_issued_property_ajax_get
                    if ($pathinfo === '/workforce/properties/ajax') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_issued_property_ajax_get;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::ajaxFilterPropertyAction',  '_route' => 'hris_workforce_issued_property_ajax_get',);
                    }
                    not_hris_workforce_issued_property_ajax_get:

                    // hris_workforce_issued_property_ajax_grid
                    if (preg_match('#^/workforce/properties/ajax/(?P<id>[^/]++)/(?P<item_name>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_issued_property_ajax_grid;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_issued_property_ajax_grid')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IssuedPropertyController::gridIssuedAction',));
                    }
                    not_hris_workforce_issued_property_ajax_grid:

                }

            }

            if (0 === strpos($pathinfo, '/workforce/reimburse')) {
                // hris_workforce_reimbursement_index
                if ($pathinfo === '/workforce/reimburses') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_reimbursement_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::indexAction',  '_route' => 'hris_workforce_reimbursement_index',);
                }
                not_hris_workforce_reimbursement_index:

                // hris_workforce_reimbursement_add_form
                if ($pathinfo === '/workforce/reimburse') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_reimbursement_add_form;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::addFormAction',  '_route' => 'hris_workforce_reimbursement_add_form',);
                }
                not_hris_workforce_reimbursement_add_form:

                // hris_workforce_reimbursement_add_submit
                if ($pathinfo === '/workforce/reimburse') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_reimbursement_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::addSubmitAction',  '_route' => 'hris_workforce_reimbursement_add_submit',);
                }
                not_hris_workforce_reimbursement_add_submit:

                // hris_workforce_reimbursement_edit_form
                if (preg_match('#^/workforce/reimburse/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_reimbursement_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_reimbursement_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::editFormAction',));
                }
                not_hris_workforce_reimbursement_edit_form:

                // hris_workforce_reimbursement_edit_submit
                if (preg_match('#^/workforce/reimburse/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_reimbursement_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_reimbursement_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::editSubmitAction',));
                }
                not_hris_workforce_reimbursement_edit_submit:

                // hris_workforce_reimbursement_delete
                if (preg_match('#^/workforce/reimburse/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_reimbursement_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_reimbursement_delete')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::deleteAction',));
                }
                not_hris_workforce_reimbursement_delete:

                // hris_workforce_reimbursement_grid
                if ($pathinfo === '/workforce/reimburses/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_reimbursement_grid;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::gridAction',  '_route' => 'hris_workforce_reimbursement_grid',);
                }
                not_hris_workforce_reimbursement_grid:

            }

            if (0 === strpos($pathinfo, '/workforce/ajax/reimburses')) {
                // hris_workforce_reimbursement_ajax_get
                if (preg_match('#^/workforce/ajax/reimburses/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_reimbursement_ajax_get;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_reimbursement_ajax_get')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::ajaxGetAction',));
                }
                not_hris_workforce_reimbursement_ajax_get:

                // hris_workforce_reimbursement_ajax_add
                if ($pathinfo === '/workforce/ajax/reimburses/add') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_reimbursement_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::ajaxAddAction',  '_route' => 'hris_workforce_reimbursement_ajax_add',);
                }
                not_hris_workforce_reimbursement_ajax_add:

            }

            if (0 === strpos($pathinfo, '/workforce/reimburse')) {
                // hris_workforce_reimbursement_status
                if (preg_match('#^/workforce/reimburse/(?P<id>[^/]++)/status/(?P<status>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_reimbursement_status;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_reimbursement_status')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::statusUpdateAction',));
                }
                not_hris_workforce_reimbursement_status:

                // hris_workforce_reimbursement_ajax_grid
                if (0 === strpos($pathinfo, '/workforce/reimburses/ajax') && preg_match('#^/workforce/reimburses/ajax/(?P<id>[^/]++)/(?P<date_from>[^/]++)/(?P<date_to>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_reimbursement_ajax_grid')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::gridReimbursementAction',));
                }

                // hris_workforce_reimbursement_print
                if (preg_match('#^/workforce/reimburse/(?P<id>[^/]++)/print/?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_reimbursement_print;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'hris_workforce_reimbursement_print');
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_reimbursement_print')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ReimbursementController::printReimbursementAction',));
                }
                not_hris_workforce_reimbursement_print:

            }

            if (0 === strpos($pathinfo, '/workforce/cash-advance')) {
                // hris_workforce_cashadvance_index
                if ($pathinfo === '/workforce/cash-advances') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_cashadvance_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AdvanceController::indexAction',  '_route' => 'hris_workforce_cashadvance_index',);
                }
                not_hris_workforce_cashadvance_index:

                // hris_workforce_cashadvance_add_form
                if ($pathinfo === '/workforce/cash-advance') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_cashadvance_add_form;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AdvanceController::addFormAction',  '_route' => 'hris_workforce_cashadvance_add_form',);
                }
                not_hris_workforce_cashadvance_add_form:

                // hris_workforce_cashadvance_add_submit
                if ($pathinfo === '/workforce/cash-advance') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_cashadvance_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AdvanceController::addSubmitAction',  '_route' => 'hris_workforce_cashadvance_add_submit',);
                }
                not_hris_workforce_cashadvance_add_submit:

                // hris_workforce_cashadvance_edit_form
                if (preg_match('#^/workforce/cash\\-advance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_cashadvance_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_cashadvance_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AdvanceController::editFormAction',));
                }
                not_hris_workforce_cashadvance_edit_form:

                // hris_workforce_cashadvance_edit_submit
                if (preg_match('#^/workforce/cash\\-advance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_cashadvance_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_cashadvance_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AdvanceController::editSubmitAction',));
                }
                not_hris_workforce_cashadvance_edit_submit:

                // hris_workforce_cashadvance_grid
                if ($pathinfo === '/workforce/cash-advance/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_cashadvance_grid;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AdvanceController::gridAction',  '_route' => 'hris_workforce_cashadvance_grid',);
                }
                not_hris_workforce_cashadvance_grid:

                // hris_workforce_cashadvance_delete
                if (preg_match('#^/workforce/cash\\-advance/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_cashadvance_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_cashadvance_delete')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AdvanceController::deleteAction',));
                }
                not_hris_workforce_cashadvance_delete:

                // hris_workforce_cashadvance_ajax_grid
                if (0 === strpos($pathinfo, '/workforce/cash-advances/ajax') && preg_match('#^/workforce/cash\\-advances/ajax/(?P<id>[^/]++)/(?P<date_from>[^/]++)/(?P<date_to>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_cashadvance_ajax_grid')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AdvanceController::gridAdvanceAction',));
                }

                // hris_workforce_cashadvance_status
                if (preg_match('#^/workforce/cash\\-advance/(?P<id>[^/]++)/status/(?P<status>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_cashadvance_status;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_cashadvance_status')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\AdvanceController::statusUpdateAction',));
                }
                not_hris_workforce_cashadvance_status:

            }

            if (0 === strpos($pathinfo, '/workforce/onboard')) {
                // hris_workforce_onboard_index
                if ($pathinfo === '/workforce/onboard') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_onboard_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OnboardController::indexAction',  '_route' => 'hris_workforce_onboard_index',);
                }
                not_hris_workforce_onboard_index:

                // hris_workforce_onboard_grid
                if ($pathinfo === '/workforce/onboard/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_onboard_grid;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OnboardController::gridAction',  '_route' => 'hris_workforce_onboard_grid',);
                }
                not_hris_workforce_onboard_grid:

            }

            // hris_workforce_onboard_edit_form
            if (0 === strpos($pathinfo, '/workforce/employee') && preg_match('#^/workforce/employee/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_workforce_onboard_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_onboard_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\EmployeeController::editFormAction',));
            }
            not_hris_workforce_onboard_edit_form:

            if (0 === strpos($pathinfo, '/workforce/resignation')) {
                // hris_workforce_resign_index
                if ($pathinfo === '/workforce/resignations') {
                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ResignController::indexAction',  '_route' => 'hris_workforce_resign_index',);
                }

                // hris_workforce_resign_add_form
                if ($pathinfo === '/workforce/resignation') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_resign_add_form;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ResignController::addFormAction',  '_route' => 'hris_workforce_resign_add_form',);
                }
                not_hris_workforce_resign_add_form:

                // hris_workforce_resign_add_submit
                if ($pathinfo === '/workforce/resignation') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_resign_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ResignController::addSubmitAction',  '_route' => 'hris_workforce_resign_add_submit',);
                }
                not_hris_workforce_resign_add_submit:

                // hris_workforce_resign_edit_form
                if (preg_match('#^/workforce/resignation/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_resign_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_resign_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ResignController::editFormAction',));
                }
                not_hris_workforce_resign_edit_form:

                // hris_workforce_resign_edit_submit
                if (preg_match('#^/workforce/resignation/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_resign_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_resign_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ResignController::editSubmitAction',));
                }
                not_hris_workforce_resign_edit_submit:

                // hris_workforce_resign_details
                if ($pathinfo === '/workforce/resignation/detail') {
                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ResignController::detailsAction',  '_route' => 'hris_workforce_resign_details',);
                }

                // hris_workforce_resign_grid
                if ($pathinfo === '/workforce/resignations/grid') {
                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ResignController::gridAction',  '_route' => 'hris_workforce_resign_grid',);
                }

            }

            // hris_workforce_resign_coe
            if (0 === strpos($pathinfo, '/workforce/ajax/coe') && preg_match('#^/workforce/ajax/coe/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_workforce_resign_coe;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_resign_coe')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ResignController::ajaxRequestCOEAction',));
            }
            not_hris_workforce_resign_coe:

            if (0 === strpos($pathinfo, '/workforce/resignation/req')) {
                // hris_profile_coe_request_add_form
                if (preg_match('#^/workforce/resignation/req(?:/(?P<type>[^/]++)(?:/(?P<emp_id>[^/]++))?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_coe_request_add_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_coe_request_add_form')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::resignCOEAction',  'type' => NULL,  'emp_id' => NULL,));
                }
                not_hris_profile_coe_request_add_form:

                // hris_profile_coe_request_add_submit
                if (preg_match('#^/workforce/resignation/req/(?P<type>[^/]++)/(?P<emp_id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_profile_coe_request_add_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_coe_request_add_submit')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::requestSubmitAction',  'type' => NULL,));
                }
                not_hris_profile_coe_request_add_submit:

            }

            if (0 === strpos($pathinfo, '/workforce/clearance')) {
                // hris_workforce_exit_clearance_print
                if (0 === strpos($pathinfo, '/workforce/clearance/print') && preg_match('#^/workforce/clearance/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_exit_clearance_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_exit_clearance_print')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ExitClearanceController::printClearanceAction',));
                }
                not_hris_workforce_exit_clearance_print:

                // hris_workforce_exit_clearance_index
                if ($pathinfo === '/workforce/clearances') {
                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ExitClearanceController::indexAction',  '_route' => 'hris_workforce_exit_clearance_index',);
                }

                // hris_workforce_exit_clearance_add_form
                if ($pathinfo === '/workforce/clearance') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_exit_clearance_add_form;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ExitClearanceController::addFormAction',  '_route' => 'hris_workforce_exit_clearance_add_form',);
                }
                not_hris_workforce_exit_clearance_add_form:

                // hris_workforce_exit_clearance_add_submit
                if ($pathinfo === '/workforce/clearance') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_exit_clearance_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ExitClearanceController::addSubmitAction',  '_route' => 'hris_workforce_exit_clearance_add_submit',);
                }
                not_hris_workforce_exit_clearance_add_submit:

                // hris_workforce_exit_clearance_edit_form
                if (preg_match('#^/workforce/clearance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_exit_clearance_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_exit_clearance_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ExitClearanceController::editFormAction',));
                }
                not_hris_workforce_exit_clearance_edit_form:

                // hris_workforce_exit_clearance_edit_submit
                if (preg_match('#^/workforce/clearance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_exit_clearance_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_exit_clearance_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ExitClearanceController::editSubmitAction',));
                }
                not_hris_workforce_exit_clearance_edit_submit:

                // hris_workforce_exit_clearance_details
                if ($pathinfo === '/workforce/clearance/detail') {
                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ExitClearanceController::detailsAction',  '_route' => 'hris_workforce_exit_clearance_details',);
                }

                // hris_workforce_exit_clearance_grid
                if ($pathinfo === '/workforce/clearances/grid') {
                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\ExitClearanceController::gridAction',  '_route' => 'hris_workforce_exit_clearance_grid',);
                }

            }

            if (0 === strpos($pathinfo, '/workforce/leave')) {
                // hris_workforce_leave_index
                if ($pathinfo === '/workforce/leaves') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_leave_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\LeaveController::indexAction',  '_route' => 'hris_workforce_leave_index',);
                }
                not_hris_workforce_leave_index:

                // hris_workforce_leave_add_form
                if ($pathinfo === '/workforce/leave') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_leave_add_form;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\LeaveController::addFormAction',  '_route' => 'hris_workforce_leave_add_form',);
                }
                not_hris_workforce_leave_add_form:

                // hris_workforce_leave_add_submit
                if ($pathinfo === '/workforce/leave') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_leave_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\LeaveController::addSubmitAction',  '_route' => 'hris_workforce_leave_add_submit',);
                }
                not_hris_workforce_leave_add_submit:

                // hris_workforce_leave_edit_form
                if (preg_match('#^/workforce/leave/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_leave_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_leave_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\LeaveController::editFormAction',));
                }
                not_hris_workforce_leave_edit_form:

                // hris_workforce_leave_edit_submit
                if (preg_match('#^/workforce/leave/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_leave_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_leave_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\LeaveController::editSubmitAction',));
                }
                not_hris_workforce_leave_edit_submit:

                // hris_workforce_leave_delete
                if (preg_match('#^/workforce/leave/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_leave_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_leave_delete')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\LeaveController::deleteAction',));
                }
                not_hris_workforce_leave_delete:

                // hris_workforce_leave_grid
                if ($pathinfo === '/workforce/leaves/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_leave_grid;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\LeaveController::gridAction',  '_route' => 'hris_workforce_leave_grid',);
                }
                not_hris_workforce_leave_grid:

            }

            if (0 === strpos($pathinfo, '/workforce/ajax/leaves')) {
                // hris_workforce_leave_ajax_get
                if (preg_match('#^/workforce/ajax/leaves/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_leave_ajax_get;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_leave_ajax_get')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\LeaveController::ajaxGetAction',));
                }
                not_hris_workforce_leave_ajax_get:

                // hris_workforce_leave_ajax_add
                if ($pathinfo === '/workforce/ajax/leaves/add') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_leave_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\LeaveController::ajaxAddAction',  '_route' => 'hris_workforce_leave_ajax_add',);
                }
                not_hris_workforce_leave_ajax_add:

            }

            if (0 === strpos($pathinfo, '/workforce/incident')) {
                // hris_workforce_incident_index
                if ($pathinfo === '/workforce/incidents') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_incident_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IncidentReportController::indexAction',  '_route' => 'hris_workforce_incident_index',);
                }
                not_hris_workforce_incident_index:

                // hris_workforce_incident_add_form
                if ($pathinfo === '/workforce/incident') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_incident_add_form;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IncidentReportController::addFormAction',  '_route' => 'hris_workforce_incident_add_form',);
                }
                not_hris_workforce_incident_add_form:

                // hris_workforce_incident_add_submit
                if ($pathinfo === '/workforce/incident') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_incident_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IncidentReportController::addSubmitAction',  '_route' => 'hris_workforce_incident_add_submit',);
                }
                not_hris_workforce_incident_add_submit:

                // hris_workforce_incident_edit_form
                if (preg_match('#^/workforce/incident/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_incident_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_incident_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IncidentReportController::editFormAction',));
                }
                not_hris_workforce_incident_edit_form:

                // hris_workforce_incident_edit_submit
                if (preg_match('#^/workforce/incident/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_incident_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_incident_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IncidentReportController::editSubmitAction',));
                }
                not_hris_workforce_incident_edit_submit:

                // hris_workforce_incident_delete
                if (preg_match('#^/workforce/incident/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_incident_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_incident_delete')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IncidentReportController::deleteAction',));
                }
                not_hris_workforce_incident_delete:

                // hris_workforce_incident_grid
                if ($pathinfo === '/workforce/incidents/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_incident_grid;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IncidentReportController::gridAction',  '_route' => 'hris_workforce_incident_grid',);
                }
                not_hris_workforce_incident_grid:

            }

            if (0 === strpos($pathinfo, '/workforce/ajax/incidents')) {
                // hris_workforce_incident_ajax_get
                if (preg_match('#^/workforce/ajax/incidents/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_incident_ajax_get;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_incident_ajax_get')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IncidentReportController::ajaxGetAction',));
                }
                not_hris_workforce_incident_ajax_get:

                // hris_workforce_incident_ajax_add
                if ($pathinfo === '/workforce/ajax/incidents/add') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_incident_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\IncidentReportController::ajaxAddAction',  '_route' => 'hris_workforce_incident_ajax_add',);
                }
                not_hris_workforce_incident_ajax_add:

            }

            if (0 === strpos($pathinfo, '/workforce/training')) {
                // hris_workforce_training_monitoring_delete
                if (preg_match('#^/workforce/training/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_training_monitoring_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_training_monitoring_delete')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TrainingMonitoringController::deleteAction',));
                }
                not_hris_workforce_training_monitoring_delete:

                // hris_workforce_training_monitoring_index
                if ($pathinfo === '/workforce/training_monitoring') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_training_monitoring_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TrainingMonitoringController::indexAction',  '_route' => 'hris_workforce_training_monitoring_index',);
                }
                not_hris_workforce_training_monitoring_index:

                // hris_workforce_training_employee_details
                if (0 === strpos($pathinfo, '/workforce/training/employee') && preg_match('#^/workforce/training/employee(?:/(?P<length>[^/]++)(?:/(?P<dept>[^/]++))?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_training_employee_details;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_training_employee_details')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TrainingMonitoringController::getEmployeesAction',  'length' => NULL,  'dept' => NULL,));
                }
                not_hris_workforce_training_employee_details:

                // hris_workforce_training_monitoring_add_submit
                if ($pathinfo === '/workforce/training') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_training_monitoring_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TrainingMonitoringController::addSubmitAction',  '_route' => 'hris_workforce_training_monitoring_add_submit',);
                }
                not_hris_workforce_training_monitoring_add_submit:

                // hris_workforce_training_monitoring_edit_submit
                if (preg_match('#^/workforce/training/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_training_monitoring_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_training_monitoring_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TrainingMonitoringController::editSubmitAction',));
                }
                not_hris_workforce_training_monitoring_edit_submit:

                // hris_workforce_training_monitoring_add_form
                if ($pathinfo === '/workforce/training') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_training_monitoring_add_form;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TrainingMonitoringController::addFormAction',  '_route' => 'hris_workforce_training_monitoring_add_form',);
                }
                not_hris_workforce_training_monitoring_add_form:

                // hris_workforce_training_monitoring_edit_form
                if (preg_match('#^/workforce/training/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_training_monitoring_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_training_monitoring_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TrainingMonitoringController::editFormAction',));
                }
                not_hris_workforce_training_monitoring_edit_form:

                if (0 === strpos($pathinfo, '/workforce/training/response')) {
                    // hris_workforce_training_monitoring_invite_response
                    if (preg_match('#^/workforce/training/response/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_training_monitoring_invite_response;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_training_monitoring_invite_response')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TrainingMonitoringController::invitationResponseAction',));
                    }
                    not_hris_workforce_training_monitoring_invite_response:

                    // hris_workforce_training_monitoring_response_submit
                    if (preg_match('#^/workforce/training/response/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_workforce_training_monitoring_response_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_training_monitoring_response_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TrainingMonitoringController::responseSubmitAction',));
                    }
                    not_hris_workforce_training_monitoring_response_submit:

                }

                if (0 === strpos($pathinfo, '/workforce/training_monitoring/grid')) {
                    // hris_workforce_training_monitoring_grid
                    if ($pathinfo === '/workforce/training_monitoring/grid') {
                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TrainingMonitoringController::gridAction',  '_route' => 'hris_workforce_training_monitoring_grid',);
                    }

                    // hris_workforce_training_monitoring_employee_grid
                    if (0 === strpos($pathinfo, '/workforce/training_monitoring/grid/employee') && preg_match('#^/workforce/training_monitoring/grid/employee(?:/(?P<name>[^/]++)(?:/(?P<dept>[^/]++)(?:/(?P<job_level>[^/]++)(?:/(?P<emp_status>[^/]++))?)?)?)?$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_training_monitoring_employee_grid')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TrainingMonitoringController::employeeGridAction',  'name' => NULL,  'dept' => NULL,  'job_level' => NULL,  'emp_status' => NULL,));
                    }

                }

            }

            if (0 === strpos($pathinfo, '/workforce/o')) {
                // hris_workforce_offset_index
                if ($pathinfo === '/workforce/offsets') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_offset_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OffsetController::indexAction',  '_route' => 'hris_workforce_offset_index',);
                }
                not_hris_workforce_offset_index:

                if (0 === strpos($pathinfo, '/workforce/overtime')) {
                    // hris_workforce_offset_add_submit
                    if ($pathinfo === '/workforce/overtime') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_workforce_offset_add_submit;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OffsetController::addSubmitAction',  '_route' => 'hris_workforce_offset_add_submit',);
                    }
                    not_hris_workforce_offset_add_submit:

                    // hris_workforce_offset_edit_submit
                    if (preg_match('#^/workforce/overtime/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_workforce_offset_edit_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_offset_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OffsetController::editSubmitAction',));
                    }
                    not_hris_workforce_offset_edit_submit:

                }

            }

            // hris_workforce_offset_add_form
            if ($pathinfo === '/workforce/batch/offset') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_workforce_offset_add_form;
                }

                return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OffsetController::offsetFormAction',  '_route' => 'hris_workforce_offset_add_form',);
            }
            not_hris_workforce_offset_add_form:

            if (0 === strpos($pathinfo, '/workforce/o')) {
                // hris_workforce_overtime_index
                if ($pathinfo === '/workforce/overtimes') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_overtime_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OvertimeController::indexAction',  '_route' => 'hris_workforce_overtime_index',);
                }
                not_hris_workforce_overtime_index:

                if (0 === strpos($pathinfo, '/workforce/offset')) {
                    // hris_workforce_offset_attendance_details
                    if (0 === strpos($pathinfo, '/workforce/offset/att/details') && preg_match('#^/workforce/offset/att/details/(?P<emp>[^/]++)/(?P<date>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_offset_attendance_details')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OffsetController::getAttendanceDetailsAction',));
                    }

                    // hris_workforce_offset_get_employee_per_department
                    if (0 === strpos($pathinfo, '/workforce/offset/emps') && preg_match('#^/workforce/offset/emps(?:/(?P<dept_id>[^/]++)(?:/(?P<loc>[^/]++)(?:/(?P<date>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_offset_get_employee_per_department;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_offset_get_employee_per_department')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OffsetController::getEmployeePerDepartmentAction',  'dept_id' => NULL,  'loc' => NULL,  'date' => NULL,));
                    }
                    not_hris_workforce_offset_get_employee_per_department:

                }

                if (0 === strpos($pathinfo, '/workforce/overtime')) {
                    // hris_workforce_overtime_fill_table
                    if (0 === strpos($pathinfo, '/workforce/overtime/employee') && preg_match('#^/workforce/overtime/employee(?:/(?P<loc>[^/]++)(?:/(?P<dept>[^/]++)(?:/(?P<pos>[^/]++)(?:/(?P<level>[^/]++))?)?)?)?$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_overtime_fill_table;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_overtime_fill_table')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OvertimeController::fillTableAction',  'loc' => NULL,  'dept' => NULL,  'pos' => NULL,  'level' => NULL,));
                    }
                    not_hris_workforce_overtime_fill_table:

                    // hris_workforce_overtime_search_grid
                    if (0 === strpos($pathinfo, '/workforce/overtime/search/grid') && preg_match('#^/workforce/overtime/search/grid(?:/(?P<branch>[^/]++)(?:/(?P<sched>[^/]++))?)?$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_overtime_search_grid;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_overtime_search_grid')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OvertimeController::filterEmployeesAction',  'branch' => NULL,  'sched' => NULL,));
                    }
                    not_hris_workforce_overtime_search_grid:

                    // hris_workforce_overtime_add_submit
                    if ($pathinfo === '/workforce/overtime') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_workforce_overtime_add_submit;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OvertimeController::addSubmitAction',  '_route' => 'hris_workforce_overtime_add_submit',);
                    }
                    not_hris_workforce_overtime_add_submit:

                    // hris_workforce_overtime_edit_submit
                    if (preg_match('#^/workforce/overtime/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_workforce_overtime_edit_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_overtime_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OvertimeController::editSubmitAction',));
                    }
                    not_hris_workforce_overtime_edit_submit:

                    // hris_workforce_overtime_add_form
                    if ($pathinfo === '/workforce/overtime') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_overtime_add_form;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OvertimeController::addFormAction',  '_route' => 'hris_workforce_overtime_add_form',);
                    }
                    not_hris_workforce_overtime_add_form:

                }

            }

            if (0 === strpos($pathinfo, '/workforce/batch/o')) {
                // hris_workforce_batchfile_overtime_index
                if ($pathinfo === '/workforce/batch/overtime') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_batchfile_overtime_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OvertimeController::addFormAction',  '_route' => 'hris_workforce_batchfile_overtime_index',);
                }
                not_hris_workforce_batchfile_overtime_index:

                // hris_workforce_batchfile_offset_index
                if ($pathinfo === '/workforce/batch/offset') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_batchfile_offset_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OffsetController::offsetFormAction',  '_route' => 'hris_workforce_batchfile_offset_index',);
                }
                not_hris_workforce_batchfile_offset_index:

                if (0 === strpos($pathinfo, '/workforce/batch/overtime')) {
                    // hris_workforce_batchfile_overtime_add_submit
                    if ($pathinfo === '/workforce/batch/overtime') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_workforce_batchfile_overtime_add_submit;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OvertimeController::addOvertimeAction',  '_route' => 'hris_workforce_batchfile_overtime_add_submit',);
                    }
                    not_hris_workforce_batchfile_overtime_add_submit:

                    // hris_workforce_overtime_batch_save
                    if ($pathinfo === '/workforce/batch/overtime/save') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_workforce_overtime_batch_save;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OvertimeController::saveBatchOvertimeAction',  '_route' => 'hris_workforce_overtime_batch_save',);
                    }
                    not_hris_workforce_overtime_batch_save:

                }

                // hris_workforce_offset_batch_save
                if ($pathinfo === '/workforce/batch/offset/save') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_offset_batch_save;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OffsetController::saveBatchOffsetAction',  '_route' => 'hris_workforce_offset_batch_save',);
                }
                not_hris_workforce_offset_batch_save:

            }

            // hris_workforce_employee_ajax_details
            if (0 === strpos($pathinfo, '/workforce/overtime/ajax/details') && preg_match('#^/workforce/overtime/ajax/details(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_employee_ajax_details')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\OffsetController::ajaxEmployeeDetailsAction',  'id' => NULL,));
            }

            if (0 === strpos($pathinfo, '/workforce/201-file')) {
                // hris_workforce_201_index
                if ($pathinfo === '/workforce/201-files') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_201_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\Employee201Controller::indexAction',  '_route' => 'hris_workforce_201_index',);
                }
                not_hris_workforce_201_index:

                // hris_workforce_201_view_form
                if (preg_match('#^/workforce/201\\-file/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_201_view_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_201_view_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\Employee201Controller::editFormAction',));
                }
                not_hris_workforce_201_view_form:

                // hris_workforce_201_print_form
                if (preg_match('#^/workforce/201\\-file/(?P<id>[^/]++)/(?P<files>[^/]++)/(?P<pays>[^/]++)/print$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_201_print_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_201_print_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\Employee201Controller::print201Action',  'id' => NULL,  'files' => NULL,  'pays' => NULL,));
                }
                not_hris_workforce_201_print_form:

                // hris_workforce_201_grid
                if ($pathinfo === '/workforce/201-files/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_201_grid;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\Employee201Controller::gridAction',  '_route' => 'hris_workforce_201_grid',);
                }
                not_hris_workforce_201_grid:

            }

            // hris_workforce_201_ajax_get
            if (0 === strpos($pathinfo, '/workforce/ajax/201-files') && preg_match('#^/workforce/ajax/201\\-files/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_workforce_201_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_201_ajax_get')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\Employee201Controller::ajaxGetAction',));
            }
            not_hris_workforce_201_ajax_get:

            if (0 === strpos($pathinfo, '/workforce/transfer')) {
                // hris_workforce_transfer_search_grid
                if (0 === strpos($pathinfo, '/workforce/transfer/search/grid') && preg_match('#^/workforce/transfer/search/grid(?:/(?P<location_from>[^/]++)(?:/(?P<id>[^/]++)(?:/(?P<id2>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_transfer_search_grid;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_transfer_search_grid')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TransferController::filterEmployeesAction',  'location_from' => NULL,  'id' => NULL,  'id2' => NULL,));
                }
                not_hris_workforce_transfer_search_grid:

                // hris_workforce_transfer_edit_grid
                if (0 === strpos($pathinfo, '/workforce/transfer/edit') && preg_match('#^/workforce/transfer/edit(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_transfer_edit_grid;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_transfer_edit_grid')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TransferController::listEmployeesAction',  'id' => NULL,));
                }
                not_hris_workforce_transfer_edit_grid:

                // hris_workforce_transfer_index
                if ($pathinfo === '/workforce/transfers') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_transfer_index;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TransferController::indexAction',  '_route' => 'hris_workforce_transfer_index',);
                }
                not_hris_workforce_transfer_index:

                // hris_workforce_transfer_add_form
                if ($pathinfo === '/workforce/transfer') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_transfer_add_form;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TransferController::addFormAction',  '_route' => 'hris_workforce_transfer_add_form',);
                }
                not_hris_workforce_transfer_add_form:

                // hris_workforce_transfer_add_submit
                if ($pathinfo === '/workforce/transfer') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_transfer_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TransferController::addSubmitAction',  '_route' => 'hris_workforce_transfer_add_submit',);
                }
                not_hris_workforce_transfer_add_submit:

                // hris_workforce_transfer_grid
                if ($pathinfo === '/workforce/transfer/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_transfer_grid;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TransferController::gridAction',  '_route' => 'hris_workforce_transfer_grid',);
                }
                not_hris_workforce_transfer_grid:

                // hris_workforce_transfer_delete
                if (preg_match('#^/workforce/transfer/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_transfer_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_transfer_delete')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TransferController::deleteAction',));
                }
                not_hris_workforce_transfer_delete:

                // hris_workforce_transfer_edit_form
                if (preg_match('#^/workforce/transfer/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_transfer_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_transfer_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TransferController::editFormAction',));
                }
                not_hris_workforce_transfer_edit_form:

                // hris_workforce_transfer_edit_submit
                if (preg_match('#^/workforce/transfer/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_workforce_transfer_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_transfer_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TransferController::editSubmitAction',));
                }
                not_hris_workforce_transfer_edit_submit:

                // hris_workforce_transfer_ajax_grid
                if (0 === strpos($pathinfo, '/workforce/transfers/ajax') && preg_match('#^/workforce/transfers/ajax(?:/(?P<location_from>[^/]++)(?:/(?P<location_to>[^/]++)(?:/(?P<transfer_type>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_transfer_ajax_grid')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\TransferController::gridTransfersAction',  'location_from' => NULL,  'location_to' => NULL,  'transfer_type' => NULL,));
                }

            }

            if (0 === strpos($pathinfo, '/workforce/memo')) {
                if (0 === strpos($pathinfo, '/workforce/memo/create')) {
                    // hris_memo_create_form
                    if (preg_match('#^/workforce/memo/create(?:/(?P<type>[^/]++)(?:/(?P<data_field>[^/]++))?)?$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_memo_create_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_create_form')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\MemoController::createMemoAction',  'type' => NULL,  'data_field' => NULL,));
                    }
                    not_hris_memo_create_form:

                    // hris_memo_create_submit
                    if (preg_match('#^/workforce/memo/create(?:/(?P<type>[^/]++)(?:/(?P<data_field>[^/]++))?)?$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_memo_create_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_create_submit')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\MemoController::createSubmitAction',  'type' => NULL,  'data_field' => NULL,));
                    }
                    not_hris_memo_create_submit:

                }

                // hris_memo_print
                if (0 === strpos($pathinfo, '/workforce/memo/print') && preg_match('#^/workforce/memo/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_memo_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_print')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\MemoController::printMemoAction',));
                }
                not_hris_memo_print:

                // hris_memo_grid
                if ($pathinfo === '/workforce/memo/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_memo_grid;
                    }

                    return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\MemoController::gridAction',  '_route' => 'hris_memo_grid',);
                }
                not_hris_memo_grid:

                // hris_memo_edit_form
                if (preg_match('#^/workforce/memo/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_memo_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_edit_form')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\MemoController::editFormAction',));
                }
                not_hris_memo_edit_form:

                // hris_memo_edit_submit
                if (preg_match('#^/workforce/memo/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_memo_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_edit_submit')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\MemoController::editSubmitAction',));
                }
                not_hris_memo_edit_submit:

                // hris_memo_delete
                if (preg_match('#^/workforce/memo/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_memo_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_delete')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\MemoController::deleteAction',));
                }
                not_hris_memo_delete:

                // hris_memo_get_agent
                if (0 === strpos($pathinfo, '/workforce/memo/agency') && preg_match('#^/workforce/memo/agency(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_memo_get_agent;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_get_agent')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\MemoController::isContractualAction',  'id' => NULL,));
                }
                not_hris_memo_get_agent:

                if (0 === strpos($pathinfo, '/workforce/memos')) {
                    if (0 === strpos($pathinfo, '/workforce/memos/company')) {
                        // hris_memo_company_index
                        if ($pathinfo === '/workforce/memos/companylist') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\CompanyMemoController::indexAction',  '_route' => 'hris_memo_company_index',);
                        }

                        // hris_memo_company_add_form
                        if ($pathinfo === '/workforce/memos/company') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_company_add_form;
                            }

                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\CompanyMemoController::addFormAction',  '_route' => 'hris_memo_company_add_form',);
                        }
                        not_hris_memo_company_add_form:

                        // hris_memo_company_add_submit
                        if ($pathinfo === '/workforce/memos/company') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_company_add_submit;
                            }

                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\CompanyMemoController::addSubmitAction',  '_route' => 'hris_memo_company_add_submit',);
                        }
                        not_hris_memo_company_add_submit:

                        // hris_memo_company_edit_form
                        if (preg_match('#^/workforce/memos/company/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_company_edit_form;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_company_edit_form')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\CompanyMemoController::editFormAction',));
                        }
                        not_hris_memo_company_edit_form:

                        // hris_memo_company_edit_submit
                        if (preg_match('#^/workforce/memos/company/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_company_edit_submit;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_company_edit_submit')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\CompanyMemoController::editSubmitAction',));
                        }
                        not_hris_memo_company_edit_submit:

                        // hris_memo_company_print
                        if (preg_match('#^/workforce/memos/company/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_company_print;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_company_print')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\CompanyMemoController::printPdfAction',));
                        }
                        not_hris_memo_company_print:

                        // hris_memo_company_delete
                        if (preg_match('#^/workforce/memos/company/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_company_delete;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_company_delete')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\CompanyMemoController::deleteAction',));
                        }
                        not_hris_memo_company_delete:

                        // hris_memo_company_grid
                        if ($pathinfo === '/workforce/memos/companylist/grid') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\CompanyMemoController::gridAction',  '_route' => 'hris_memo_company_grid',);
                        }

                    }

                    if (0 === strpos($pathinfo, '/workforce/memos/violation')) {
                        // hris_memo_violation_index
                        if ($pathinfo === '/workforce/memos/violationlist') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\ViolationController::indexAction',  '_route' => 'hris_memo_violation_index',);
                        }

                        // hris_memo_violation_add_form
                        if ($pathinfo === '/workforce/memos/violation') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_violation_add_form;
                            }

                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\ViolationController::addFormAction',  '_route' => 'hris_memo_violation_add_form',);
                        }
                        not_hris_memo_violation_add_form:

                        // hris_memo_violation_add_submit
                        if ($pathinfo === '/workforce/memos/violation') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_violation_add_submit;
                            }

                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\ViolationController::addSubmitAction',  '_route' => 'hris_memo_violation_add_submit',);
                        }
                        not_hris_memo_violation_add_submit:

                        // hris_memo_violation_edit_form
                        if (preg_match('#^/workforce/memos/violation/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_violation_edit_form;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_violation_edit_form')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\ViolationController::editFormAction',));
                        }
                        not_hris_memo_violation_edit_form:

                        // hris_memo_violation_edit_submit
                        if (preg_match('#^/workforce/memos/violation/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_violation_edit_submit;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_violation_edit_submit')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\ViolationController::editSubmitAction',));
                        }
                        not_hris_memo_violation_edit_submit:

                        // hris_memo_violation_delete
                        if (preg_match('#^/workforce/memos/violation/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_violation_delete;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_violation_delete')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\ViolationController::deleteAction',));
                        }
                        not_hris_memo_violation_delete:

                        // hris_memo_violation_print
                        if (preg_match('#^/workforce/memos/violation/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_violation_print;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_violation_print')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\ViolationController::printPdfAction',));
                        }
                        not_hris_memo_violation_print:

                        // hris_memo_violation_grid
                        if ($pathinfo === '/workforce/memos/violationlist/grid') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\ViolationController::gridAction',  '_route' => 'hris_memo_violation_grid',);
                        }

                    }

                    if (0 === strpos($pathinfo, '/workforce/memos/disciplinary')) {
                        // hris_memo_disciplinary_index
                        if ($pathinfo === '/workforce/memos/disciplinarylist') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\DisciplinaryController::indexAction',  '_route' => 'hris_memo_disciplinary_index',);
                        }

                        // hris_memo_disciplinary_add_form
                        if ($pathinfo === '/workforce/memos/disciplinary') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_disciplinary_add_form;
                            }

                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\DisciplinaryController::addFormAction',  '_route' => 'hris_memo_disciplinary_add_form',);
                        }
                        not_hris_memo_disciplinary_add_form:

                        // hris_memo_disciplinary_add_submit
                        if ($pathinfo === '/workforce/memos/disciplinary') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_disciplinary_add_submit;
                            }

                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\DisciplinaryController::addSubmitAction',  '_route' => 'hris_memo_disciplinary_add_submit',);
                        }
                        not_hris_memo_disciplinary_add_submit:

                        // hris_memo_disciplinary_edit_form
                        if (preg_match('#^/workforce/memos/disciplinary/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_disciplinary_edit_form;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_disciplinary_edit_form')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\DisciplinaryController::editFormAction',));
                        }
                        not_hris_memo_disciplinary_edit_form:

                        // hris_memo_disciplinary_edit_submit
                        if (preg_match('#^/workforce/memos/disciplinary/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_disciplinary_edit_submit;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_disciplinary_edit_submit')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\DisciplinaryController::editSubmitAction',));
                        }
                        not_hris_memo_disciplinary_edit_submit:

                        // hris_memo_disciplinary_delete
                        if (preg_match('#^/workforce/memos/disciplinary/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_disciplinary_delete;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_disciplinary_delete')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\DisciplinaryController::deleteAction',));
                        }
                        not_hris_memo_disciplinary_delete:

                        // hris_memo_disciplinary_print
                        if (preg_match('#^/workforce/memos/disciplinary/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_disciplinary_print;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_disciplinary_print')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\DisciplinaryController::printPdfAction',));
                        }
                        not_hris_memo_disciplinary_print:

                        // hris_memo_disciplinary_grid
                        if ($pathinfo === '/workforce/memos/disciplinarylist/grid') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\DisciplinaryController::gridAction',  '_route' => 'hris_memo_disciplinary_grid',);
                        }

                    }

                    if (0 === strpos($pathinfo, '/workforce/memos/tardiness')) {
                        // hris_memo_tardiness_index
                        if ($pathinfo === '/workforce/memos/tardinesslist') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\TardinessController::indexAction',  '_route' => 'hris_memo_tardiness_index',);
                        }

                        if (0 === strpos($pathinfo, '/workforce/memos/tardiness/create')) {
                            // hris_memo_tardiness_add_form
                            if (preg_match('#^/workforce/memos/tardiness/create(?:/(?P<data_field>[^/]++))?$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_memo_tardiness_add_form;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_tardiness_add_form')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\TardinessController::createTardinessMemoAction',  'data_field' => NULL,));
                            }
                            not_hris_memo_tardiness_add_form:

                            // hris_memo_tardiness_add_submit
                            if (preg_match('#^/workforce/memos/tardiness/create(?:/(?P<data_field>[^/]++))?$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_hris_memo_tardiness_add_submit;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_tardiness_add_submit')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\TardinessController::addSubmitAction',  'data_field' => NULL,));
                            }
                            not_hris_memo_tardiness_add_submit:

                        }

                        // hris_memo_tardiness_edit_form
                        if (preg_match('#^/workforce/memos/tardiness/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_tardiness_edit_form;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_tardiness_edit_form')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\TardinessController::editFormAction',));
                        }
                        not_hris_memo_tardiness_edit_form:

                        // hris_memo_tardiness_edit_submit
                        if (preg_match('#^/workforce/memos/tardiness/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_tardiness_edit_submit;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_tardiness_edit_submit')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\TardinessController::editSubmitAction',));
                        }
                        not_hris_memo_tardiness_edit_submit:

                        // hris_memo_tardiness_delete
                        if (preg_match('#^/workforce/memos/tardiness/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_tardiness_delete;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_tardiness_delete')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\TardinessController::deleteAction',));
                        }
                        not_hris_memo_tardiness_delete:

                        // hris_memo_tardiness_print
                        if (preg_match('#^/workforce/memos/tardiness/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_tardiness_print;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_tardiness_print')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\TardinessController::printPdfAction',));
                        }
                        not_hris_memo_tardiness_print:

                        // hris_memo_tardiness_grid
                        if ($pathinfo === '/workforce/memos/tardinesslist/grid') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\TardinessController::gridAction',  '_route' => 'hris_memo_tardiness_grid',);
                        }

                    }

                    if (0 === strpos($pathinfo, '/workforce/memos/promotion')) {
                        // hris_memo_promotion_index
                        if ($pathinfo === '/workforce/memos/promotionlist') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\PromotionController::indexAction',  '_route' => 'hris_memo_promotion_index',);
                        }

                        if (0 === strpos($pathinfo, '/workforce/memos/promotion/create')) {
                            // hris_memo_promotion_add_form
                            if (preg_match('#^/workforce/memos/promotion/create(?:/(?P<emp_id>[^/]++))?$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_memo_promotion_add_form;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_promotion_add_form')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\PromotionController::createPromotionMemoAction',  'emp_id' => NULL,));
                            }
                            not_hris_memo_promotion_add_form:

                            // hris_memo_promotion_add_submit
                            if (preg_match('#^/workforce/memos/promotion/create/(?P<emp_id>[^/]++)$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_hris_memo_promotion_add_submit;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_promotion_add_submit')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\PromotionController::addSubmitAction',));
                            }
                            not_hris_memo_promotion_add_submit:

                        }

                        // hris_memo_promotion_edit_form
                        if (preg_match('#^/workforce/memos/promotion/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_promotion_edit_form;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_promotion_edit_form')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\PromotionController::editFormAction',));
                        }
                        not_hris_memo_promotion_edit_form:

                        // hris_memo_promotion_edit_submit
                        if (preg_match('#^/workforce/memos/promotion/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_promotion_edit_submit;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_promotion_edit_submit')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\PromotionController::editSubmitAction',));
                        }
                        not_hris_memo_promotion_edit_submit:

                        // hris_memo_promotion_delete
                        if (preg_match('#^/workforce/memos/promotion/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_promotion_delete;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_promotion_delete')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\PromotionController::deleteAction',));
                        }
                        not_hris_memo_promotion_delete:

                        // hris_memo_promotion_print
                        if (preg_match('#^/workforce/memos/promotion/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_promotion_print;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_promotion_print')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\PromotionController::printPdfAction',));
                        }
                        not_hris_memo_promotion_print:

                        // hris_memo_promotion_grid
                        if ($pathinfo === '/workforce/memos/promotionlist/grid') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\PromotionController::gridAction',  '_route' => 'hris_memo_promotion_grid',);
                        }

                    }

                    if (0 === strpos($pathinfo, '/workforce/memos/regularization')) {
                        // hris_memo_regularization_index
                        if ($pathinfo === '/workforce/memos/regularizationlist') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\RegularizationController::indexAction',  '_route' => 'hris_memo_regularization_index',);
                        }

                        if (0 === strpos($pathinfo, '/workforce/memos/regularization/create')) {
                            // hris_memo_regularization_add_form
                            if (preg_match('#^/workforce/memos/regularization/create(?:/(?P<emp_id>[^/]++))?$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_memo_regularization_add_form;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_regularization_add_form')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\RegularizationController::createRegMemoAction',  'emp_id' => NULL,));
                            }
                            not_hris_memo_regularization_add_form:

                            // hris_memo_regularization_add_submit
                            if (preg_match('#^/workforce/memos/regularization/create/(?P<emp_id>[^/]++)$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_hris_memo_regularization_add_submit;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_regularization_add_submit')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\RegularizationController::addSubmitAction',));
                            }
                            not_hris_memo_regularization_add_submit:

                        }

                        // hris_memo_regularization_edit_form
                        if (preg_match('#^/workforce/memos/regularization/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_regularization_edit_form;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_regularization_edit_form')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\RegularizationController::editFormAction',));
                        }
                        not_hris_memo_regularization_edit_form:

                        // hris_memo_regularization_edit_submit
                        if (preg_match('#^/workforce/memos/regularization/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_regularization_edit_submit;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_regularization_edit_submit')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\RegularizationController::editSubmitAction',));
                        }
                        not_hris_memo_regularization_edit_submit:

                        // hris_memo_regularization_delete
                        if (preg_match('#^/workforce/memos/regularization/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_memo_regularization_delete;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_regularization_delete')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\RegularizationController::deleteAction',));
                        }
                        not_hris_memo_regularization_delete:

                        // hris_memo_regularization_print
                        if (preg_match('#^/workforce/memos/regularization/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_regularization_print;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_regularization_print')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\RegularizationController::printPdfAction',));
                        }
                        not_hris_memo_regularization_print:

                        // hris_memo_regularization_grid
                        if ($pathinfo === '/workforce/memos/regularizationlist/grid') {
                            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\RegularizationController::gridAction',  '_route' => 'hris_memo_regularization_grid',);
                        }

                    }

                    if (0 === strpos($pathinfo, '/workforce/memos/per')) {
                        // hris_memo_personnel_notice_index
                        if ($pathinfo === '/workforce/memos/personnelnoticelist') {
                            return array (  '_controller' => 'UnicommMemoBundle:PersonnelNotice:index',  '_route' => 'hris_memo_personnel_notice_index',);
                        }

                        if (0 === strpos($pathinfo, '/workforce/memos/peronnelnotice')) {
                            // hris_memo_personnel_notice_add_form
                            if ($pathinfo === '/workforce/memos/peronnelnotice') {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_memo_personnel_notice_add_form;
                                }

                                return array (  '_controller' => 'UnicommMemoBundle:PersonnelNotice:addForm',  '_route' => 'hris_memo_personnel_notice_add_form',);
                            }
                            not_hris_memo_personnel_notice_add_form:

                            // hris_memo_personnel_notice_add_submit
                            if ($pathinfo === '/workforce/memos/peronnelnotice') {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_hris_memo_personnel_notice_add_submit;
                                }

                                return array (  '_controller' => 'UnicommMemoBundle:PersonnelNotice:addSubmit',  '_route' => 'hris_memo_personnel_notice_add_submit',);
                            }
                            not_hris_memo_personnel_notice_add_submit:

                            // hris_memo_personnel_notice_edit_form
                            if (preg_match('#^/workforce/memos/peronnelnotice/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_memo_personnel_notice_edit_form;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_personnel_notice_edit_form')), array (  '_controller' => 'UnicommMemoBundle:PersonnelNotice:editForm',));
                            }
                            not_hris_memo_personnel_notice_edit_form:

                            // hris_memo_personnel_notice_edit_submit
                            if (preg_match('#^/workforce/memos/peronnelnotice/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_hris_memo_personnel_notice_edit_submit;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_personnel_notice_edit_submit')), array (  '_controller' => 'UnicommMemoBundle:PersonnelNotice:editSubmit',));
                            }
                            not_hris_memo_personnel_notice_edit_submit:

                            // hris_memo_personnel_notice_delete
                            if (preg_match('#^/workforce/memos/peronnelnotice/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_memo_personnel_notice_delete;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_personnel_notice_delete')), array (  '_controller' => 'UnicommMemoBundle:PersonnelNotice:delete',));
                            }
                            not_hris_memo_personnel_notice_delete:

                        }

                        // hris_memo_personnel_notice_print
                        if (0 === strpos($pathinfo, '/workforce/memos/personnelnoticelist') && preg_match('#^/workforce/memos/personnelnoticelist/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_personnel_notice_print;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_personnel_notice_print')), array (  '_controller' => 'UnicommMemoBundle:PersonnelNotice:printPdf',));
                        }
                        not_hris_memo_personnel_notice_print:

                        // hris_memo_personnel_notice_grid
                        if ($pathinfo === '/workforce/memos/per/grid') {
                            return array (  '_controller' => 'UnicommMemoBundle:PersonnelNotice:grid',  '_route' => 'hris_memo_personnel_notice_grid',);
                        }

                    }

                    if (0 === strpos($pathinfo, '/workforce/memos/review')) {
                        // hris_memo_disciplinary_change_status
                        if (0 === strpos($pathinfo, '/workforce/memos/review/disciplinary') && preg_match('#^/workforce/memos/review/disciplinary(?:/(?P<id>[^/]++)(?:/(?P<type>[^/]++)(?:/(?P<status>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_disciplinary_change_status;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_disciplinary_change_status')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\DisciplinaryController::updateStatusAction',  'id' => NULL,  'type' => NULL,  'status' => NULL,));
                        }
                        not_hris_memo_disciplinary_change_status:

                        // hris_memo_violation_change_status
                        if (0 === strpos($pathinfo, '/workforce/memos/review/violation') && preg_match('#^/workforce/memos/review/violation(?:/(?P<id>[^/]++)(?:/(?P<type>[^/]++)(?:/(?P<status>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_violation_change_status;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_violation_change_status')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\ViolationController::updateStatusAction',  'id' => NULL,  'type' => NULL,  'status' => NULL,));
                        }
                        not_hris_memo_violation_change_status:

                        // hris_memo_personnel_notice_change_status
                        if (0 === strpos($pathinfo, '/workforce/memos/review/personnelNotice') && preg_match('#^/workforce/memos/review/personnelNotice(?:/(?P<id>[^/]++)(?:/(?P<type>[^/]++)(?:/(?P<status>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_personnel_notice_change_status;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_personnel_notice_change_status')), array (  '_controller' => 'UnicommMemoBundle:PersonnelNotice:updateStatus',  'id' => NULL,  'type' => NULL,  'status' => NULL,));
                        }
                        not_hris_memo_personnel_notice_change_status:

                        // hris_memo_regularization_change_status
                        if (0 === strpos($pathinfo, '/workforce/memos/review/regularization') && preg_match('#^/workforce/memos/review/regularization(?:/(?P<id>[^/]++)(?:/(?P<type>[^/]++)(?:/(?P<status>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_regularization_change_status;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_regularization_change_status')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\RegularizationController::updateStatusAction',  'id' => NULL,  'type' => NULL,  'status' => NULL,));
                        }
                        not_hris_memo_regularization_change_status:

                        // hris_memo_promotion_change_status
                        if (0 === strpos($pathinfo, '/workforce/memos/review/promotion') && preg_match('#^/workforce/memos/review/promotion(?:/(?P<id>[^/]++)(?:/(?P<type>[^/]++)(?:/(?P<status>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_memo_promotion_change_status;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_memo_promotion_change_status')), array (  '_controller' => 'Hris\\MemoBundle\\Controller\\PromotionController::updateStatusAction',  'id' => NULL,  'type' => NULL,  'status' => NULL,));
                        }
                        not_hris_memo_promotion_change_status:

                    }

                }

            }

            if (0 === strpos($pathinfo, '/workforce/requests')) {
                // hris_workforce_request_index
                if ($pathinfo === '/workforce/requests/list') {
                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\RequestController::indexAction',  '_route' => 'hris_workforce_request_index',);
                }

                // hris_workforce_request_grid
                if ($pathinfo === '/workforce/requests/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_request_grid;
                    }

                    return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\RequestController::gridAction',  '_route' => 'hris_workforce_request_grid',);
                }
                not_hris_workforce_request_grid:

                if (0 === strpos($pathinfo, '/workforce/requests/add')) {
                    // hris_workforce_request_add_form
                    if ($pathinfo === '/workforce/requests/add') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_request_add_form;
                        }

                        return array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\RequestController::addFormAction',  'type' => NULL,  '_route' => 'hris_workforce_request_add_form',);
                    }
                    not_hris_workforce_request_add_form:

                    // hris_workforce_request_add_submit
                    if (preg_match('#^/workforce/requests/add(?:/(?P<type>[^/]++))?$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_workforce_request_add_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_request_add_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\RequestController::addSubmitAction',  'type' => NULL,));
                    }
                    not_hris_workforce_request_add_submit:

                }

                if (0 === strpos($pathinfo, '/workforce/requests/edit')) {
                    // hris_workforce_request_edit_form
                    if (preg_match('#^/workforce/requests/edit/(?P<id>[^/]++)/(?P<type>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_workforce_request_edit_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_request_edit_form')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\RequestController::viewFormAction',));
                    }
                    not_hris_workforce_request_edit_form:

                    // hris_workforce_request_edit_submit
                    if (preg_match('#^/workforce/requests/edit/(?P<id>[^/]++)/(?P<type>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_workforce_request_edit_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_request_edit_submit')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\RequestController::viewSubmitAction',));
                    }
                    not_hris_workforce_request_edit_submit:

                }

                // hris_workforce_request_status
                if (0 === strpos($pathinfo, '/workforce/requests/status') && preg_match('#^/workforce/requests/status/(?P<id>[^/]++)/(?P<type>[^/]++)/(?P<status>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_workforce_request_status;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_request_status')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\RequestController::updateStatusAction',));
                }
                not_hris_workforce_request_status:

                // hris_workforce_request_print
                if (0 === strpos($pathinfo, '/workforce/requests/print') && preg_match('#^/workforce/requests/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_workforce_request_print')), array (  '_controller' => 'Hris\\WorkforceBundle\\Controller\\RequestController::printFormAction',));
                }

            }

        }

        if (0 === strpos($pathinfo, '/attendance')) {
            // hris_attendance_emp-attendance_add_form
            if ($pathinfo === '/attendance/attendance') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_attendance_empattendance_add_form;
                }

                return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::addFormAction',  '_route' => 'hris_attendance_emp-attendance_add_form',);
            }
            not_hris_attendance_empattendance_add_form:

            if (0 === strpos($pathinfo, '/attendance/dtr/import')) {
                // hris_attendance_import_dtr_form
                if ($pathinfo === '/attendance/dtr/import') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_attendance_import_dtr_form;
                    }

                    return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::importDTRFormAction',  '_route' => 'hris_attendance_import_dtr_form',);
                }
                not_hris_attendance_import_dtr_form:

                // hris_attendance_import_dtr
                if ($pathinfo === '/attendance/dtr/import') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_attendance_import_dtr;
                    }

                    return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::importDTRAction',  '_route' => 'hris_attendance_import_dtr',);
                }
                not_hris_attendance_import_dtr:

            }

            // hris_attendance_dtr_progress
            if ($pathinfo === '/attendance/progress') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_attendance_dtr_progress;
                }

                return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::getProgressAction',  '_route' => 'hris_attendance_dtr_progress',);
            }
            not_hris_attendance_dtr_progress:

            if (0 === strpos($pathinfo, '/attendance/attendance')) {
                // hris_attendance_emp-attendance_add_submit
                if ($pathinfo === '/attendance/attendance') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_attendance_empattendance_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::addSubmitAction',  '_route' => 'hris_attendance_emp-attendance_add_submit',);
                }
                not_hris_attendance_empattendance_add_submit:

                // hris_attendance_emp-attendance_edit_form
                if (preg_match('#^/attendance/attendance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_attendance_empattendance_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_emp-attendance_edit_form')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::editFormAction',));
                }
                not_hris_attendance_empattendance_edit_form:

                // hris_attendance_emp-attendance_edit_submit
                if (preg_match('#^/attendance/attendance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_attendance_empattendance_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_emp-attendance_edit_submit')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::editSubmitAction',));
                }
                not_hris_attendance_empattendance_edit_submit:

                if (0 === strpos($pathinfo, '/attendance/attendances')) {
                    // hris_attendance_emp-attendance_grid
                    if ($pathinfo === '/attendance/attendances/grid') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_attendance_empattendance_grid;
                        }

                        return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::gridAction',  '_route' => 'hris_attendance_emp-attendance_grid',);
                    }
                    not_hris_attendance_empattendance_grid:

                    // hris_attendance_emp-attendance_print
                    if (0 === strpos($pathinfo, '/attendance/attendances/print') && preg_match('#^/attendance/attendances/print(?:/(?P<id>[^/]++)(?:/(?P<dfrom>[^/]++)(?:/(?P<dto>[^/]++)(?:/(?P<dept>[^/]++))?)?)?)?$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_attendance_empattendance_print;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_emp-attendance_print')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::printAttendanceAction',  'id' => NULL,  'dfrom' => NULL,  'dto' => NULL,  'dept' => NULL,));
                    }
                    not_hris_attendance_empattendance_print:

                }

                // hris_attendance_emp-attendance_delete
                if (preg_match('#^/attendance/attendance/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_attendance_empattendance_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_emp-attendance_delete')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::deleteAction',));
                }
                not_hris_attendance_empattendance_delete:

                // hris_attendance_emp-attendance_view
                if ($pathinfo === '/attendance/attendance/view') {
                    return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::viewAction',  '_route' => 'hris_attendance_emp-attendance_view',);
                }

                // hris_attendance_emp-attendance_ajax_grid
                if (0 === strpos($pathinfo, '/attendance/attendances/ajax') && preg_match('#^/attendance/attendances/ajax(?:/(?P<id>[^/]++)(?:/(?P<department>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++))?)?)?)?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_emp-attendance_ajax_grid')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::gridAttendancesAction',  'id' => NULL,  'department' => NULL,  'date_from' => NULL,  'date_to' => NULL,));
                }

                // hris_attendance_emp-attendance_ajax
                if (preg_match('#^/attendance/attendance(?:/(?P<id>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_emp-attendance_ajax')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::gridAttendanceAction',  'id' => NULL,  'date_from' => NULL,  'date_to' => NULL,));
                }

            }

            // hris_attendance_import_dtr_submit
            if ($pathinfo === '/attendance/dtr/import') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_attendance_import_dtr_submit;
                }

                return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::importDTRSubmitAction',  '_route' => 'hris_attendance_import_dtr_submit',);
            }
            not_hris_attendance_import_dtr_submit:

            // hris_attendance_emp-attendance_bio_ajax
            if ($pathinfo === '/attendance/bio/ajax') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_attendance_empattendance_bio_ajax;
                }

                return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::bioAjaxAction',  '_route' => 'hris_attendance_emp-attendance_bio_ajax',);
            }
            not_hris_attendance_empattendance_bio_ajax:

            // hris_attendance_get_schedules
            if (0 === strpos($pathinfo, '/attendance/get_schedules') && preg_match('#^/attendance/get_schedules(?:/(?P<loc>[^/]++))?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_attendance_get_schedules;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_get_schedules')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\CreateScheduleController::getSchedulesAction',  'loc' => NULL,));
            }
            not_hris_attendance_get_schedules:

            if (0 === strpos($pathinfo, '/attendance/create_schedule')) {
                // hris_attendance_schedule_index
                if ($pathinfo === '/attendance/create_schedule') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_attendance_schedule_index;
                    }

                    return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\CreateScheduleController::indexAction',  '_route' => 'hris_attendance_schedule_index',);
                }
                not_hris_attendance_schedule_index:

                // hris_attendance_schedule_filter
                if (preg_match('#^/attendance/create_schedule(?:/(?P<loc>[^/]++)(?:/(?P<dept>[^/]++)(?:/(?P<pos>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_attendance_schedule_filter;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_schedule_filter')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\CreateScheduleController::filterEmployeesAction',  'loc' => NULL,  'dept' => NULL,  'pos' => NULL,));
                }
                not_hris_attendance_schedule_filter:

                // hris_attendance_scheduled_emp
                if (0 === strpos($pathinfo, '/attendance/create_schedules') && preg_match('#^/attendance/create_schedules(?:/(?P<date>[^/]++))?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_attendance_scheduled_emp;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_scheduled_emp')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\CreateScheduleController::ScheduledEmployeesAction',  'date' => NULL,));
                }
                not_hris_attendance_scheduled_emp:

            }

            // hris_attendance_delete_emp
            if ($pathinfo === '/attendance/delete_schedule') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_attendance_delete_emp;
                }

                return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\CreateScheduleController::deleteScheduleAction',  '_route' => 'hris_attendance_delete_emp',);
            }
            not_hris_attendance_delete_emp:

            // hris_attendance_save_schedule
            if ($pathinfo === '/attendance/save_schedule') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_attendance_save_schedule;
                }

                return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\CreateScheduleController::saveScheduleAction',  '_route' => 'hris_attendance_save_schedule',);
            }
            not_hris_attendance_save_schedule:

            // hris_attendance_calendar_click
            if (0 === strpos($pathinfo, '/attendance/calendar_click') && preg_match('#^/attendance/calendar_click(?:/(?P<date>[^/]++)(?:/(?P<loc>[^/]++))?)?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_attendance_calendar_click;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_calendar_click')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\CreateScheduleController::CalendarClickAction',  'date' => NULL,  'loc' => NULL,));
            }
            not_hris_attendance_calendar_click:

            // hris_attendance_event_click
            if (0 === strpos($pathinfo, '/attendance/event_click') && preg_match('#^/attendance/event_click(?:/(?P<date>[^/]++)(?:/(?P<shift>[^/]++))?)?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_attendance_event_click;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_event_click')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\CreateScheduleController::EventClickAction',  'date' => NULL,  'shift' => NULL,));
            }
            not_hris_attendance_event_click:

            // hris_attendance_schedule_print
            if (0 === strpos($pathinfo, '/attendance/schedule/print') && preg_match('#^/attendance/schedule/print(?:/(?P<loc>[^/]++)(?:/(?P<date>[^/]++))?)?$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_schedule_print')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\CreateScheduleController::printSchedulesAction',  'loc' => NULL,  'date' => NULL,));
            }

            if (0 === strpos($pathinfo, '/attendance/break_schedule')) {
                // hris_attendance_break_index
                if ($pathinfo === '/attendance/break_schedule') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_attendance_break_index;
                    }

                    return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\BreakScheduleController::indexAction',  '_route' => 'hris_attendance_break_index',);
                }
                not_hris_attendance_break_index:

                // hris_attendance_break_submit
                if ($pathinfo === '/attendance/break_schedule') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_attendance_break_submit;
                    }

                    return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\BreakScheduleController::breakSubmitAction',  '_route' => 'hris_attendance_break_submit',);
                }
                not_hris_attendance_break_submit:

            }

            if (0 === strpos($pathinfo, '/attendance/weekly')) {
                // hris_attendance_weekly_index
                if (rtrim($pathinfo, '/') === '/attendance/weekly') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_attendance_weekly_index;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'hris_attendance_weekly_index');
                    }

                    return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\WeeklyScheduleController::indexAction',  '_route' => 'hris_attendance_weekly_index',);
                }
                not_hris_attendance_weekly_index:

                // hris_attendance_weekly_submit
                if ($pathinfo === '/attendance/weekly/') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_attendance_weekly_submit;
                    }

                    return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\WeeklyScheduleController::saveScheduleAction',  '_route' => 'hris_attendance_weekly_submit',);
                }
                not_hris_attendance_weekly_submit:

                // hris_attendance_weekly_employees
                if (0 === strpos($pathinfo, '/attendance/weekly/employees') && preg_match('#^/attendance/weekly/employees/(?P<branch_id>[^/]++)/(?P<week_start>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_attendance_weekly_employees;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_weekly_employees')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\WeeklyScheduleController::getEmployeesBranchAction',));
                }
                not_hris_attendance_weekly_employees:

                // hris_attendance_weekly_print
                if (0 === strpos($pathinfo, '/attendance/weekly/print') && preg_match('#^/attendance/weekly/print/(?P<branch_id>[^/]++)/(?P<week_start>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_attendance_weekly_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_attendance_weekly_print')), array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\WeeklyScheduleController::printAction',));
                }
                not_hris_attendance_weekly_print:

            }

        }

        // hris_notification_homepage
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?P<name>[^/]++)$#s', $pathinfo, $matches)) {
            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_notification_homepage')), array (  '_controller' => 'HrisNotificationBundle:Default:index',));
        }

        if (0 === strpos($pathinfo, '/custom_approval/setting')) {
            // hris_admin_custom_approval_settings_index
            if ($pathinfo === '/custom_approval/settings') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\CustomApprovalController::indexAction',  '_route' => 'hris_admin_custom_approval_settings_index',);
            }

            // hris_admin_custom_approval_settings_add_form
            if ($pathinfo === '/custom_approval/setting') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_custom_approval_settings_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\CustomApprovalController::addFormAction',  '_route' => 'hris_admin_custom_approval_settings_add_form',);
            }
            not_hris_admin_custom_approval_settings_add_form:

            // hris_admin_custom_approval_settings_add_submit
            if ($pathinfo === '/custom_approval/setting') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_custom_approval_settings_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\CustomApprovalController::addSubmitAction',  '_route' => 'hris_admin_custom_approval_settings_add_submit',);
            }
            not_hris_admin_custom_approval_settings_add_submit:

            // hris_admin_custom_approval_settings_edit_form
            if (preg_match('#^/custom_approval/setting/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_custom_approval_settings_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_custom_approval_settings_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\CustomApprovalController::editFormAction',));
            }
            not_hris_admin_custom_approval_settings_edit_form:

            // hris_admin_custom_approval_settings_edit_submit
            if (preg_match('#^/custom_approval/setting/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_custom_approval_settings_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_custom_approval_settings_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\CustomApprovalController::editSubmitAction',));
            }
            not_hris_admin_custom_approval_settings_edit_submit:

            // hris_admin_custom_approval_settings_delete
            if (preg_match('#^/custom_approval/setting/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_custom_approval_settings_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_custom_approval_settings_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\CustomApprovalController::deleteAction',));
            }
            not_hris_admin_custom_approval_settings_delete:

            // hris_admin_custom_approval_settings_grid
            if ($pathinfo === '/custom_approval/settings/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_custom_approval_settings_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\CustomApprovalController::gridAction',  '_route' => 'hris_admin_custom_approval_settings_grid',);
            }
            not_hris_admin_custom_approval_settings_grid:

        }

        if (0 === strpos($pathinfo, '/ajax/custom_approval/setting')) {
            // hris_admin_custom_approval_settings_ajax_get_form
            if (preg_match('#^/ajax/custom_approval/setting/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_custom_approval_settings_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_custom_approval_settings_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\CustomApprovalController::ajaxGetFormAction',));
            }
            not_hris_admin_custom_approval_settings_ajax_get_form:

            // hris_admin_custom_approval_settings_ajax_add
            if ($pathinfo === '/ajax/custom_approval/setting') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_custom_approval_settings_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\CustomApprovalController::ajaxAddAction',  '_route' => 'hris_admin_custom_approval_settings_ajax_add',);
            }
            not_hris_admin_custom_approval_settings_ajax_add:

            // hris_admin_custom_approval_settings_ajax_save
            if (preg_match('#^/ajax/custom_approval/setting/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_custom_approval_settings_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_custom_approval_settings_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\CustomApprovalController::ajaxSaveAction',));
            }
            not_hris_admin_custom_approval_settings_ajax_save:

        }

        if (0 === strpos($pathinfo, '/department')) {
            // hris_admin_department_index
            if ($pathinfo === '/departments') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::indexAction',  '_route' => 'hris_admin_department_index',);
            }

            // hris_admin_department_add_form
            if ($pathinfo === '/department') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_department_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::addFormAction',  '_route' => 'hris_admin_department_add_form',);
            }
            not_hris_admin_department_add_form:

            // hris_admin_department_add_submit
            if ($pathinfo === '/department') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_department_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::addSubmitAction',  '_route' => 'hris_admin_department_add_submit',);
            }
            not_hris_admin_department_add_submit:

            // hris_admin_department_edit_form
            if (preg_match('#^/department/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_department_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_department_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::editFormAction',));
            }
            not_hris_admin_department_edit_form:

            // hris_admin_department_edit_submit
            if (preg_match('#^/department/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_department_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_department_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::editSubmitAction',));
            }
            not_hris_admin_department_edit_submit:

            // hris_admin_department_delete
            if (preg_match('#^/department/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_department_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_department_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::deleteAction',));
            }
            not_hris_admin_department_delete:

            // hris_admin_department_grid
            if ($pathinfo === '/departments/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_department_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::gridAction',  '_route' => 'hris_admin_department_grid',);
            }
            not_hris_admin_department_grid:

        }

        if (0 === strpos($pathinfo, '/a')) {
            if (0 === strpos($pathinfo, '/ajax/dep')) {
                if (0 === strpos($pathinfo, '/ajax/department')) {
                    // hris_admin_department_ajax_get
                    if (preg_match('#^/ajax/department/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_admin_department_ajax_get;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_department_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::ajaxGetAction',));
                    }
                    not_hris_admin_department_ajax_get:

                    // hris_admin_employee_ajax_filter
                    if (0 === strpos($pathinfo, '/ajax/departmenthead') && preg_match('#^/ajax/departmenthead(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_admin_employee_ajax_filter;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_employee_ajax_filter')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::ajaxFilterDepartmentHeadAction',  'id' => NULL,));
                    }
                    not_hris_admin_employee_ajax_filter:

                }

                if (0 === strpos($pathinfo, '/ajax/dept')) {
                    // hris_admin_department_ajax_get_form
                    if (preg_match('#^/ajax/dept/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_admin_department_ajax_get_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_department_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::ajaxGetFormAction',));
                    }
                    not_hris_admin_department_ajax_get_form:

                    // hris_admin_department_ajax_add
                    if ($pathinfo === '/ajax/dept') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_admin_department_ajax_add;
                        }

                        return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::ajaxAddAction',  '_route' => 'hris_admin_department_ajax_add',);
                    }
                    not_hris_admin_department_ajax_add:

                    // hris_admin_department_ajax_save
                    if (preg_match('#^/ajax/dept/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_admin_department_ajax_save;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_department_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DepartmentController::ajaxSaveAction',));
                    }
                    not_hris_admin_department_ajax_save:

                }

            }

            if (0 === strpos($pathinfo, '/appraisal/setting')) {
                // hris_admin_appraisal_settings_index
                if ($pathinfo === '/appraisal/settings') {
                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AppraisalSettingsController::indexAction',  '_route' => 'hris_admin_appraisal_settings_index',);
                }

                // hris_admin_appraisal_settings_add_form
                if ($pathinfo === '/appraisal/setting') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_appraisal_settings_add_form;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AppraisalSettingsController::addFormAction',  '_route' => 'hris_admin_appraisal_settings_add_form',);
                }
                not_hris_admin_appraisal_settings_add_form:

                // hris_admin_appraisal_settings_add_submit
                if ($pathinfo === '/appraisal/setting') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_admin_appraisal_settings_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AppraisalSettingsController::addSubmitAction',  '_route' => 'hris_admin_appraisal_settings_add_submit',);
                }
                not_hris_admin_appraisal_settings_add_submit:

                // hris_admin_appraisal_settings_edit_form
                if (preg_match('#^/appraisal/setting/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_appraisal_settings_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_appraisal_settings_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AppraisalSettingsController::editFormAction',));
                }
                not_hris_admin_appraisal_settings_edit_form:

                // hris_admin_appraisal_settings_edit_submit
                if (preg_match('#^/appraisal/setting/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_admin_appraisal_settings_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_appraisal_settings_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AppraisalSettingsController::editSubmitAction',));
                }
                not_hris_admin_appraisal_settings_edit_submit:

                // hris_admin_appraisal_settings_delete
                if (preg_match('#^/appraisal/setting/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_appraisal_settings_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_appraisal_settings_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AppraisalSettingsController::deleteAction',));
                }
                not_hris_admin_appraisal_settings_delete:

                // hris_admin_appraisal_settings_grid
                if ($pathinfo === '/appraisal/settings/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_appraisal_settings_grid;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AppraisalSettingsController::gridAction',  '_route' => 'hris_admin_appraisal_settings_grid',);
                }
                not_hris_admin_appraisal_settings_grid:

            }

            // hris_admin_appraisal_settings_ajax_get
            if (0 === strpos($pathinfo, '/ajax/appraisal/setting') && preg_match('#^/ajax/appraisal/setting/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_appraisal_settings_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_appraisal_settings_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AppraisalSettingsController::ajaxGetAction',));
            }
            not_hris_admin_appraisal_settings_ajax_get:

        }

        if (0 === strpos($pathinfo, '/event')) {
            // hris_admin_events_index
            if ($pathinfo === '/events') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::indexAction',  '_route' => 'hris_admin_events_index',);
            }

            // hris_admin_events_add_form
            if ($pathinfo === '/event') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_events_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::addFormAction',  '_route' => 'hris_admin_events_add_form',);
            }
            not_hris_admin_events_add_form:

            // hris_admin_events_add_submit
            if ($pathinfo === '/event') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_events_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::addSubmitAction',  '_route' => 'hris_admin_events_add_submit',);
            }
            not_hris_admin_events_add_submit:

            // hris_admin_events_edit_form
            if (preg_match('#^/event/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_events_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_events_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::editFormAction',));
            }
            not_hris_admin_events_edit_form:

            // hris_admin_events_edit_submit
            if (preg_match('#^/event/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_events_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_events_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::editSubmitAction',));
            }
            not_hris_admin_events_edit_submit:

            // hris_admin_events_delete
            if (preg_match('#^/event/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_events_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_events_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::deleteAction',));
            }
            not_hris_admin_events_delete:

            // hris_admin_events_grid
            if ($pathinfo === '/events/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_events_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::gridAction',  '_route' => 'hris_admin_events_grid',);
            }
            not_hris_admin_events_grid:

        }

        // hris_admin_events_ajax_get
        if (0 === strpos($pathinfo, '/ajax/events') && preg_match('#^/ajax/events/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_hris_admin_events_ajax_get;
            }

            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_events_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::ajaxGetAction',));
        }
        not_hris_admin_events_ajax_get:

        // hris_admin_events_get_event_all
        if ($pathinfo === '/get/events/all') {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_hris_admin_events_get_event_all;
            }

            return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::getAllEventsAction',  '_route' => 'hris_admin_events_get_event_all',);
        }
        not_hris_admin_events_get_event_all:

        if (0 === strpos($pathinfo, '/ajax/event')) {
            if (0 === strpos($pathinfo, '/ajax/events')) {
                // hris_admin_events_ajax_get_form
                if (preg_match('#^/ajax/events/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_events_ajax_get_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_events_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::ajaxGetFormAction',));
                }
                not_hris_admin_events_ajax_get_form:

                // hris_admin_events_ajax_add
                if ($pathinfo === '/ajax/events') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_admin_events_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::ajaxAddAction',  '_route' => 'hris_admin_events_ajax_add',);
                }
                not_hris_admin_events_ajax_add:

            }

            // hris_admin_events_ajax_save
            if (preg_match('#^/ajax/event/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_events_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_events_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\EventsController::ajaxSaveAction',));
            }
            not_hris_admin_events_ajax_save:

        }

        if (0 === strpos($pathinfo, '/downloadable')) {
            // hris_admin_downloadables_index
            if ($pathinfo === '/downloadables') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DownloadablesController::indexAction',  '_route' => 'hris_admin_downloadables_index',);
            }

            // hris_admin_downloadables_add_form
            if ($pathinfo === '/downloadable') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_downloadables_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DownloadablesController::addFormAction',  '_route' => 'hris_admin_downloadables_add_form',);
            }
            not_hris_admin_downloadables_add_form:

            // hris_admin_downloadables_add_submit
            if ($pathinfo === '/downloadable') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_downloadables_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DownloadablesController::addSubmitAction',  '_route' => 'hris_admin_downloadables_add_submit',);
            }
            not_hris_admin_downloadables_add_submit:

            // hris_admin_downloadables_edit_form
            if (preg_match('#^/downloadable/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_downloadables_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_downloadables_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DownloadablesController::editFormAction',));
            }
            not_hris_admin_downloadables_edit_form:

            // hris_admin_downloadables_edit_submit
            if (preg_match('#^/downloadable/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_downloadables_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_downloadables_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DownloadablesController::editSubmitAction',));
            }
            not_hris_admin_downloadables_edit_submit:

            // hris_admin_downloadables_delete
            if (preg_match('#^/downloadable/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_downloadables_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_downloadables_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DownloadablesController::deleteAction',));
            }
            not_hris_admin_downloadables_delete:

            // hris_admin_downloadables_grid
            if ($pathinfo === '/downloadables/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_downloadables_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DownloadablesController::gridAction',  '_route' => 'hris_admin_downloadables_grid',);
            }
            not_hris_admin_downloadables_grid:

        }

        if (0 === strpos($pathinfo, '/ajax/downloadable')) {
            // hris_admin_downloadables_ajax_get
            if (preg_match('#^/ajax/downloadable/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_downloadables_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_downloadables_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DownloadablesController::ajaxGetAction',));
            }
            not_hris_admin_downloadables_ajax_get:

            // hris_admin_downloadables_ajax_get_form
            if (preg_match('#^/ajax/downloadable/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_downloadables_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_downloadables_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DownloadablesController::ajaxGetFormAction',));
            }
            not_hris_admin_downloadables_ajax_get_form:

            // hris_admin_downloadables_ajax_add
            if ($pathinfo === '/ajax/downloadable') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_downloadables_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DownloadablesController::ajaxAddAction',  '_route' => 'hris_admin_downloadables_ajax_add',);
            }
            not_hris_admin_downloadables_ajax_add:

            // hris_admin_downloadables_ajax_save
            if (preg_match('#^/ajax/downloadable/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_downloadables_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_downloadables_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\DownloadablesController::ajaxSaveAction',));
            }
            not_hris_admin_downloadables_ajax_save:

        }

        if (0 === strpos($pathinfo, '/benefit')) {
            // hris_admin_benefit_index
            if ($pathinfo === '/benefits') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::indexAction',  '_route' => 'hris_admin_benefit_index',);
            }

            // hris_admin_benefit_add_form
            if ($pathinfo === '/benefit') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::addFormAction',  '_route' => 'hris_admin_benefit_add_form',);
            }
            not_hris_admin_benefit_add_form:

            // hris_admin_benefit_add_submit
            if ($pathinfo === '/benefit') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_benefit_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::addSubmitAction',  '_route' => 'hris_admin_benefit_add_submit',);
            }
            not_hris_admin_benefit_add_submit:

            // hris_admin_benefit_edit_form
            if (preg_match('#^/benefit/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::editFormAction',));
            }
            not_hris_admin_benefit_edit_form:

            // hris_admin_benefit_edit_submit
            if (preg_match('#^/benefit/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_benefit_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::editSubmitAction',));
            }
            not_hris_admin_benefit_edit_submit:

            // hris_admin_benefit_delete
            if (preg_match('#^/benefit/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::deleteAction',));
            }
            not_hris_admin_benefit_delete:

            // hris_admin_benefit_grid
            if ($pathinfo === '/benefits/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::gridAction',  '_route' => 'hris_admin_benefit_grid',);
            }
            not_hris_admin_benefit_grid:

        }

        if (0 === strpos($pathinfo, '/ajax/benefit')) {
            // hris_admin_benefit_ajax_get
            if (preg_match('#^/ajax/benefit/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::ajaxGetAction',));
            }
            not_hris_admin_benefit_ajax_get:

            // hris_admin_benefit_ajax_get_form
            if (preg_match('#^/ajax/benefit/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::ajaxGetFormAction',));
            }
            not_hris_admin_benefit_ajax_get_form:

            // hris_admin_benefit_ajax_add
            if ($pathinfo === '/ajax/benefit') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_benefit_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::ajaxAddAction',  '_route' => 'hris_admin_benefit_ajax_add',);
            }
            not_hris_admin_benefit_ajax_add:

            // hris_admin_benefit_ajax_save
            if (preg_match('#^/ajax/benefit/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_benefit_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::ajaxSaveAction',));
            }
            not_hris_admin_benefit_ajax_save:

            // hris_admin_benefit_ajax_options
            if (0 === strpos($pathinfo, '/ajax/benefit/options') && preg_match('#^/ajax/benefit/options/(?P<id>[^/]++)(?:/(?P<ben_id>[^/]++))?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_ajax_options;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_ajax_options')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::getOptionsAction',  'ben_id' => NULL,));
            }
            not_hris_admin_benefit_ajax_options:

            // hris_admin_benefit_ajax_details
            if (0 === strpos($pathinfo, '/ajax/benefit/details') && preg_match('#^/ajax/benefit/details/(?P<id>[^/]++)/(?P<name>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_ajax_details;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_ajax_details')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitController::getDetailsAction',));
            }
            not_hris_admin_benefit_ajax_details:

        }

        if (0 === strpos($pathinfo, '/gov_benefit')) {
            // hris_admin_gov_benefit_index
            if ($pathinfo === '/gov_benefits') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::indexAction',  '_route' => 'hris_admin_gov_benefit_index',);
            }

            // hris_admin_gov_benefit_add_form
            if ($pathinfo === '/gov_benefit') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_gov_benefit_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::addFormAction',  '_route' => 'hris_admin_gov_benefit_add_form',);
            }
            not_hris_admin_gov_benefit_add_form:

            // hris_admin_gov_benefit_add_submit
            if ($pathinfo === '/gov_benefit') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_gov_benefit_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::addSubmitAction',  '_route' => 'hris_admin_gov_benefit_add_submit',);
            }
            not_hris_admin_gov_benefit_add_submit:

            // hris_admin_gov_benefit_edit_form
            if (preg_match('#^/gov_benefit/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_gov_benefit_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_gov_benefit_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::editFormAction',));
            }
            not_hris_admin_gov_benefit_edit_form:

            // hris_admin_gov_benefit_edit_submit
            if (preg_match('#^/gov_benefit/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_gov_benefit_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_gov_benefit_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::editSubmitAction',));
            }
            not_hris_admin_gov_benefit_edit_submit:

            // hris_admin_gov_benefit_delete
            if (preg_match('#^/gov_benefit/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_gov_benefit_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_gov_benefit_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::deleteAction',));
            }
            not_hris_admin_gov_benefit_delete:

            // hris_admin_gov_benefit_grid
            if ($pathinfo === '/gov_benefits/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_gov_benefit_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::gridAction',  '_route' => 'hris_admin_gov_benefit_grid',);
            }
            not_hris_admin_gov_benefit_grid:

        }

        if (0 === strpos($pathinfo, '/ajax/gov_benefit')) {
            // hris_admin_gov_benefit_ajax_get
            if (preg_match('#^/ajax/gov_benefit/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_gov_benefit_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_gov_benefit_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::ajaxGetAction',));
            }
            not_hris_admin_gov_benefit_ajax_get:

            // hris_admin_gov_benefit_ajax_get_form
            if (preg_match('#^/ajax/gov_benefit/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_gov_benefit_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_gov_benefit_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::ajaxGetFormAction',));
            }
            not_hris_admin_gov_benefit_ajax_get_form:

            // hris_admin_gov_benefit_ajax_add
            if ($pathinfo === '/ajax/gov_benefit') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_gov_benefit_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::ajaxAddAction',  '_route' => 'hris_admin_gov_benefit_ajax_add',);
            }
            not_hris_admin_gov_benefit_ajax_add:

            // hris_admin_gov_benefit_ajax_save
            if (preg_match('#^/ajax/gov_benefit/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_gov_benefit_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_gov_benefit_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::ajaxSaveAction',));
            }
            not_hris_admin_gov_benefit_ajax_save:

            // hris_admin_gov_benefit_ajax_options
            if (0 === strpos($pathinfo, '/ajax/gov_benefit/options') && preg_match('#^/ajax/gov_benefit/options/(?P<id>[^/]++)(?:/(?P<ben_id>[^/]++))?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_gov_benefit_ajax_options;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_gov_benefit_ajax_options')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::getOptionsAction',  'ben_id' => NULL,));
            }
            not_hris_admin_gov_benefit_ajax_options:

            // hris_admin_gov_benefit_ajax_details
            if (0 === strpos($pathinfo, '/ajax/gov_benefit/details') && preg_match('#^/ajax/gov_benefit/details/(?P<id>[^/]++)/(?P<name>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_gov_benefit_ajax_details;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_gov_benefit_ajax_details')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitGovernmentController::getDetailsAction',));
            }
            not_hris_admin_gov_benefit_ajax_details:

        }

        if (0 === strpos($pathinfo, '/benefit-template')) {
            // hris_admin_benefit_template_index
            if ($pathinfo === '/benefit-templates') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitTemplateController::indexAction',  '_route' => 'hris_admin_benefit_template_index',);
            }

            // hris_admin_benefit_template_add_form
            if ($pathinfo === '/benefit-template') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_template_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitTemplateController::addFormAction',  '_route' => 'hris_admin_benefit_template_add_form',);
            }
            not_hris_admin_benefit_template_add_form:

            // hris_admin_benefit_template_add_submit
            if ($pathinfo === '/benefit-template') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_benefit_template_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitTemplateController::addSubmitAction',  '_route' => 'hris_admin_benefit_template_add_submit',);
            }
            not_hris_admin_benefit_template_add_submit:

            // hris_admin_benefit_template_edit_form
            if (preg_match('#^/benefit\\-template/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_template_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_template_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitTemplateController::editFormAction',));
            }
            not_hris_admin_benefit_template_edit_form:

            // hris_admin_benefit_template_edit_submit
            if (preg_match('#^/benefit\\-template/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_benefit_template_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_template_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitTemplateController::editSubmitAction',));
            }
            not_hris_admin_benefit_template_edit_submit:

            // hris_admin_benefit_template_delete
            if (preg_match('#^/benefit\\-template/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_template_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_template_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitTemplateController::deleteAction',));
            }
            not_hris_admin_benefit_template_delete:

            // hris_admin_benefit_template_grid
            if ($pathinfo === '/benefit-templates/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_template_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitTemplateController::gridAction',  '_route' => 'hris_admin_benefit_template_grid',);
            }
            not_hris_admin_benefit_template_grid:

        }

        if (0 === strpos($pathinfo, '/ajax/benefit-template')) {
            // hris_admin_benefit_template_ajax_get
            if (preg_match('#^/ajax/benefit\\-template/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_template_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_template_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitTemplateController::ajaxGetAction',));
            }
            not_hris_admin_benefit_template_ajax_get:

            // hris_admin_benefit_template_ajax_get_form
            if (preg_match('#^/ajax/benefit\\-template/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_benefit_template_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_template_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitTemplateController::ajaxGetFormAction',));
            }
            not_hris_admin_benefit_template_ajax_get_form:

            // hris_admin_benefit_template_ajax_add
            if ($pathinfo === '/ajax/benefit-template') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_benefit_template_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitTemplateController::ajaxAddAction',  '_route' => 'hris_admin_benefit_template_ajax_add',);
            }
            not_hris_admin_benefit_template_ajax_add:

            // hris_admin_benefit_template_ajax_save
            if (preg_match('#^/ajax/benefit\\-template/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_benefit_template_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_benefit_template_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\BenefitTemplateController::ajaxSaveAction',));
            }
            not_hris_admin_benefit_template_ajax_save:

        }

        if (0 === strpos($pathinfo, '/jobtitle')) {
            // hris_admin_jobtitles_index
            if ($pathinfo === '/jobtitles') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::indexAction',  '_route' => 'hris_admin_jobtitles_index',);
            }

            // hris_admin_jobtitles_add_form
            if ($pathinfo === '/jobtitle') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_jobtitles_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::addFormAction',  '_route' => 'hris_admin_jobtitles_add_form',);
            }
            not_hris_admin_jobtitles_add_form:

            // hris_admin_jobtitles_add_submit
            if ($pathinfo === '/jobtitle') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_jobtitles_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::addSubmitAction',  '_route' => 'hris_admin_jobtitles_add_submit',);
            }
            not_hris_admin_jobtitles_add_submit:

            // hris_admin_jobtitles_edit_form
            if (preg_match('#^/jobtitle/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_jobtitles_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_jobtitles_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::editFormAction',));
            }
            not_hris_admin_jobtitles_edit_form:

            // hris_admin_jobtitles_edit_submit
            if (preg_match('#^/jobtitle/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_jobtitles_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_jobtitles_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::editSubmitAction',));
            }
            not_hris_admin_jobtitles_edit_submit:

            // hris_admin_jobtitles_delete
            if (preg_match('#^/jobtitle/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_jobtitles_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_jobtitles_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::deleteAction',));
            }
            not_hris_admin_jobtitles_delete:

            // hris_admin_jobtitles_grid
            if ($pathinfo === '/jobtitles/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_jobtitles_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::gridAction',  '_route' => 'hris_admin_jobtitles_grid',);
            }
            not_hris_admin_jobtitles_grid:

        }

        // hris_admin_jobtitles_ajax_get
        if (0 === strpos($pathinfo, '/ajax/jobtitle') && preg_match('#^/ajax/jobtitle/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_hris_admin_jobtitles_ajax_get;
            }

            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_jobtitles_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::ajaxGetAction',));
        }
        not_hris_admin_jobtitles_ajax_get:

        if (0 === strpos($pathinfo, '/jobtitles/ajax')) {
            // hris_admin_jobtitles_ajax_filter
            if ($pathinfo === '/jobtitles/ajax') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_jobtitles_ajax_filter;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::ajaxFilterJobTitleAction',  '_route' => 'hris_admin_jobtitles_ajax_filter',);
            }
            not_hris_admin_jobtitles_ajax_filter:

            // hris_admin_jobtitles_ajax_filter_dept
            if (0 === strpos($pathinfo, '/jobtitles/ajax/dept') && preg_match('#^/jobtitles/ajax/dept/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_jobtitles_ajax_filter_dept;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_jobtitles_ajax_filter_dept')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::ajaxFilterPosbyDeptAction',));
            }
            not_hris_admin_jobtitles_ajax_filter_dept:

        }

        if (0 === strpos($pathinfo, '/ajax/jobtitle')) {
            // hris_admin_jobtitles_ajax
            if ($pathinfo === '/ajax/jobtitle') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_jobtitles_ajax;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::ajaxAddAction',  '_route' => 'hris_admin_jobtitles_ajax',);
            }
            not_hris_admin_jobtitles_ajax:

            // hris_admin_jobtitles_ajax_get_form
            if (preg_match('#^/ajax/jobtitle/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_jobtitles_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_jobtitles_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::ajaxGetFormAction',));
            }
            not_hris_admin_jobtitles_ajax_get_form:

            // hris_admin_jobtitles_ajax_add
            if ($pathinfo === '/ajax/jobtitle') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_jobtitles_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::ajaxAddAction',  '_route' => 'hris_admin_jobtitles_ajax_add',);
            }
            not_hris_admin_jobtitles_ajax_add:

            // hris_admin_jobtitles_ajax_save
            if (preg_match('#^/ajax/jobtitle/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_jobtitles_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_jobtitles_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobTitleController::ajaxSaveAction',));
            }
            not_hris_admin_jobtitles_ajax_save:

        }

        if (0 === strpos($pathinfo, '/joblevel')) {
            // hris_admin_joblevel_index
            if ($pathinfo === '/joblevels') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::indexAction',  '_route' => 'hris_admin_joblevel_index',);
            }

            // hris_admin_joblevel_add_form
            if ($pathinfo === '/joblevel') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_joblevel_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::addFormAction',  '_route' => 'hris_admin_joblevel_add_form',);
            }
            not_hris_admin_joblevel_add_form:

            // hris_admin_joblevel_add_submit
            if ($pathinfo === '/joblevel') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_joblevel_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::addSubmitAction',  '_route' => 'hris_admin_joblevel_add_submit',);
            }
            not_hris_admin_joblevel_add_submit:

            // hris_admin_joblevel_edit_form
            if (preg_match('#^/joblevel/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_joblevel_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_joblevel_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::editFormAction',));
            }
            not_hris_admin_joblevel_edit_form:

            // hris_admin_joblevel_edit_submit
            if (preg_match('#^/joblevel/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_joblevel_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_joblevel_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::editSubmitAction',));
            }
            not_hris_admin_joblevel_edit_submit:

            // hris_admin_joblevel_delete
            if (preg_match('#^/joblevel/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_joblevel_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_joblevel_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::deleteAction',));
            }
            not_hris_admin_joblevel_delete:

            // hris_admin_joblevel_grid
            if ($pathinfo === '/joblevels/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_joblevel_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::gridAction',  '_route' => 'hris_admin_joblevel_grid',);
            }
            not_hris_admin_joblevel_grid:

        }

        // hris_admin_joblevel_ajax_get
        if (0 === strpos($pathinfo, '/ajax/joblevel') && preg_match('#^/ajax/joblevel/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_hris_admin_joblevel_ajax_get;
            }

            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_joblevel_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::ajaxGetAction',));
        }
        not_hris_admin_joblevel_ajax_get:

        // hris_admin_joblevel_ajax_filter
        if ($pathinfo === '/joblevels/ajax') {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_hris_admin_joblevel_ajax_filter;
            }

            return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::ajaxFilterJobTitleAction',  '_route' => 'hris_admin_joblevel_ajax_filter',);
        }
        not_hris_admin_joblevel_ajax_filter:

        if (0 === strpos($pathinfo, '/ajax/joblevel')) {
            // hris_admin_joblevel_ajax_get_form
            if (preg_match('#^/ajax/joblevel/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_joblevel_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_joblevel_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::ajaxGetFormAction',));
            }
            not_hris_admin_joblevel_ajax_get_form:

            // hris_admin_joblevel_ajax_add
            if ($pathinfo === '/ajax/joblevel') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_joblevel_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::ajaxAddAction',  '_route' => 'hris_admin_joblevel_ajax_add',);
            }
            not_hris_admin_joblevel_ajax_add:

            // hris_admin_joblevel_ajax_save
            if (preg_match('#^/ajax/joblevel/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_joblevel_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_joblevel_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\JobLevelController::ajaxSaveAction',));
            }
            not_hris_admin_joblevel_ajax_save:

        }

        if (0 === strpos($pathinfo, '/location')) {
            // hris_admin_location_index
            if ($pathinfo === '/locations') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\LocationController::indexAction',  '_route' => 'hris_admin_location_index',);
            }

            // hris_admin_location_add_form
            if ($pathinfo === '/location') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_location_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\LocationController::addFormAction',  '_route' => 'hris_admin_location_add_form',);
            }
            not_hris_admin_location_add_form:

            // hris_admin_location_add_submit
            if ($pathinfo === '/location') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_location_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\LocationController::addSubmitAction',  '_route' => 'hris_admin_location_add_submit',);
            }
            not_hris_admin_location_add_submit:

            // hris_admin_location_edit_form
            if (preg_match('#^/location/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_location_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_location_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\LocationController::editFormAction',));
            }
            not_hris_admin_location_edit_form:

            // hris_admin_location_edit_submit
            if (preg_match('#^/location/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_location_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_location_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\LocationController::editSubmitAction',));
            }
            not_hris_admin_location_edit_submit:

            // hris_admin_location_delete
            if (preg_match('#^/location/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_location_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_location_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\LocationController::deleteAction',));
            }
            not_hris_admin_location_delete:

            // hris_admin_location_grid
            if ($pathinfo === '/locations/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_location_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\LocationController::gridAction',  '_route' => 'hris_admin_location_grid',);
            }
            not_hris_admin_location_grid:

        }

        if (0 === strpos($pathinfo, '/a')) {
            if (0 === strpos($pathinfo, '/ajax/location')) {
                // hris_admin_location_ajax_get
                if (preg_match('#^/ajax/location/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_location_ajax_get;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_location_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\LocationController::ajaxGetAction',));
                }
                not_hris_admin_location_ajax_get:

                // hris_admin_location_ajax_get_form
                if (preg_match('#^/ajax/location/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_location_ajax_get_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_location_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\LocationController::ajaxGetFormAction',));
                }
                not_hris_admin_location_ajax_get_form:

                // hris_admin_location_ajax_add
                if ($pathinfo === '/ajax/location') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_admin_location_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\LocationController::ajaxAddAction',  '_route' => 'hris_admin_location_ajax_add',);
                }
                not_hris_admin_location_ajax_add:

                // hris_admin_location_ajax_save
                if (preg_match('#^/ajax/location/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_admin_location_ajax_save;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_location_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\LocationController::ajaxSaveAction',));
                }
                not_hris_admin_location_ajax_save:

            }

            if (0 === strpos($pathinfo, '/address')) {
                // hris_admin_address_index
                if ($pathinfo === '/addresses') {
                    return array (  '_controller' => 'HrisAdminBundle:Address:index',  '_route' => 'hris_admin_address_index',);
                }

                // hris_admin_address_add_form
                if ($pathinfo === '/address') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_address_add_form;
                    }

                    return array (  '_controller' => 'HrisAdminBundle:Address:addForm',  '_route' => 'hris_admin_address_add_form',);
                }
                not_hris_admin_address_add_form:

                // hris_admin_address_add_submit
                if ($pathinfo === '/address') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_admin_address_add_submit;
                    }

                    return array (  '_controller' => 'HrisAdminBundle:Address:addSubmit',  '_route' => 'hris_admin_address_add_submit',);
                }
                not_hris_admin_address_add_submit:

                // hris_admin_address_edit_form
                if (preg_match('#^/address/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_address_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_address_edit_form')), array (  '_controller' => 'HrisAdminBundle:Address:editForm',));
                }
                not_hris_admin_address_edit_form:

                // hris_admin_address_edit_submit
                if (preg_match('#^/address/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_admin_address_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_address_edit_submit')), array (  '_controller' => 'HrisAdminBundle:Address:editSubmit',));
                }
                not_hris_admin_address_edit_submit:

                // hris_admin_address_delete
                if (preg_match('#^/address/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_address_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_address_delete')), array (  '_controller' => 'HrisAdminBundle:Address:delete',));
                }
                not_hris_admin_address_delete:

                // hris_admin_address_grid
                if ($pathinfo === '/addresses/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_address_grid;
                    }

                    return array (  '_controller' => 'HrisAdminBundle:Address:grid',  '_route' => 'hris_admin_address_grid',);
                }
                not_hris_admin_address_grid:

            }

            // hris_admin_address_ajax_get
            if (0 === strpos($pathinfo, '/ajax/address') && preg_match('#^/ajax/address/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_address_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_address_ajax_get')), array (  '_controller' => 'HrisAdminBundle:Address:ajaxGet',));
            }
            not_hris_admin_address_ajax_get:

        }

        if (0 === strpos($pathinfo, '/schedule')) {
            // hris_admin_schedules_index
            if ($pathinfo === '/schedules') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SchedulesController::indexAction',  '_route' => 'hris_admin_schedules_index',);
            }

            // hris_admin_schedules_add_form
            if ($pathinfo === '/schedule') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_schedules_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SchedulesController::addFormAction',  '_route' => 'hris_admin_schedules_add_form',);
            }
            not_hris_admin_schedules_add_form:

            // hris_admin_schedules_add_submit
            if ($pathinfo === '/schedule') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_schedules_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SchedulesController::addSubmitAction',  '_route' => 'hris_admin_schedules_add_submit',);
            }
            not_hris_admin_schedules_add_submit:

            // hris_admin_schedules_edit_form
            if (preg_match('#^/schedule/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_schedules_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_schedules_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SchedulesController::editFormAction',));
            }
            not_hris_admin_schedules_edit_form:

            // hris_admin_schedules_edit_submit
            if (preg_match('#^/schedule/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_schedules_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_schedules_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SchedulesController::editSubmitAction',));
            }
            not_hris_admin_schedules_edit_submit:

            // hris_admin_schedules_delete
            if (preg_match('#^/schedule/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_schedules_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_schedules_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SchedulesController::deleteAction',));
            }
            not_hris_admin_schedules_delete:

            // hris_admin_schedules_grid
            if ($pathinfo === '/schedules/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_schedules_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SchedulesController::gridAction',  '_route' => 'hris_admin_schedules_grid',);
            }
            not_hris_admin_schedules_grid:

        }

        if (0 === strpos($pathinfo, '/ajax/schedule')) {
            // hris_admin_schedules_ajax_get
            if (preg_match('#^/ajax/schedule/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_schedules_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_schedules_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SchedulesController::ajaxGetAction',));
            }
            not_hris_admin_schedules_ajax_get:

            // hris_admin_schedules_ajax_get_form
            if (preg_match('#^/ajax/schedule/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_schedules_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_schedules_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SchedulesController::ajaxGetFormAction',));
            }
            not_hris_admin_schedules_ajax_get_form:

            // hris_admin_schedules_ajax_add
            if ($pathinfo === '/ajax/schedule') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_schedules_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SchedulesController::ajaxAddAction',  '_route' => 'hris_admin_schedules_ajax_add',);
            }
            not_hris_admin_schedules_ajax_add:

            // hris_admin_schedules_ajax_save
            if (preg_match('#^/ajax/schedule/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_schedules_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_schedules_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SchedulesController::ajaxSaveAction',));
            }
            not_hris_admin_schedules_ajax_save:

        }

        if (0 === strpos($pathinfo, '/checklist')) {
            // hris_admin_checklist_index
            if ($pathinfo === '/checklist/list') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ChecklistController::indexAction',  '_route' => 'hris_admin_checklist_index',);
            }

            // hris_admin_checklist_add_form
            if ($pathinfo === '/checklist') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_checklist_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ChecklistController::addFormAction',  '_route' => 'hris_admin_checklist_add_form',);
            }
            not_hris_admin_checklist_add_form:

            // hris_admin_checklist_add_submit
            if ($pathinfo === '/checklist') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_checklist_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ChecklistController::addSubmitAction',  '_route' => 'hris_admin_checklist_add_submit',);
            }
            not_hris_admin_checklist_add_submit:

            // hris_admin_checklist_edit_form
            if (preg_match('#^/checklist/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_checklist_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_checklist_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ChecklistController::editFormAction',));
            }
            not_hris_admin_checklist_edit_form:

            // hris_admin_checklist_edit_submit
            if (preg_match('#^/checklist/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_checklist_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_checklist_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ChecklistController::editSubmitAction',));
            }
            not_hris_admin_checklist_edit_submit:

            // hris_admin_checklist_delete
            if (preg_match('#^/checklist/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_checklist_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_checklist_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ChecklistController::deleteAction',));
            }
            not_hris_admin_checklist_delete:

            // hris_admin_checklist_grid
            if ($pathinfo === '/checklists/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_checklist_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ChecklistController::gridAction',  '_route' => 'hris_admin_checklist_grid',);
            }
            not_hris_admin_checklist_grid:

        }

        if (0 === strpos($pathinfo, '/ajax/checklist')) {
            // hris_admin_checklist_ajax_get
            if (preg_match('#^/ajax/checklist/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_checklist_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_checklist_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ChecklistController::ajaxGetAction',));
            }
            not_hris_admin_checklist_ajax_get:

            // hris_admin_checklist_ajax_get_form
            if (preg_match('#^/ajax/checklist/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_checklist_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_checklist_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ChecklistController::ajaxGetFormAction',));
            }
            not_hris_admin_checklist_ajax_get_form:

            // hris_admin_checklist_ajax_add
            if ($pathinfo === '/ajax/checklist') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_checklist_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ChecklistController::ajaxAddAction',  '_route' => 'hris_admin_checklist_ajax_add',);
            }
            not_hris_admin_checklist_ajax_add:

            // hris_admin_checklist_ajax_save
            if (preg_match('#^/ajax/checklist/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_checklist_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_checklist_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ChecklistController::ajaxSaveAction',));
            }
            not_hris_admin_checklist_ajax_save:

        }

        if (0 === strpos($pathinfo, '/holiday')) {
            // hris_admin_holiday_index
            if ($pathinfo === '/holidays') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_holiday_index;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::indexAction',  '_route' => 'hris_admin_holiday_index',);
            }
            not_hris_admin_holiday_index:

            // hris_admin_holiday_add_form
            if ($pathinfo === '/holiday') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_holiday_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::addFormAction',  '_route' => 'hris_admin_holiday_add_form',);
            }
            not_hris_admin_holiday_add_form:

            // hris_admin_holiday_add_submit
            if ($pathinfo === '/holiday') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_holiday_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::addSubmitAction',  '_route' => 'hris_admin_holiday_add_submit',);
            }
            not_hris_admin_holiday_add_submit:

            // hris_admin_holiday_edit_form
            if (preg_match('#^/holiday/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_holiday_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_holiday_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::editFormAction',));
            }
            not_hris_admin_holiday_edit_form:

            // hris_admin_holiday_edit_submit
            if (preg_match('#^/holiday/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_holiday_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_holiday_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::editSubmitAction',));
            }
            not_hris_admin_holiday_edit_submit:

            // hris_admin_holiday_delete
            if (preg_match('#^/holiday/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_holiday_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_holiday_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::deleteAction',));
            }
            not_hris_admin_holiday_delete:

            if (0 === strpos($pathinfo, '/holidays')) {
                // hris_admin_holiday_grid
                if ($pathinfo === '/holidays/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_holiday_grid;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::gridAction',  '_route' => 'hris_admin_holiday_grid',);
                }
                not_hris_admin_holiday_grid:

                // hris_admin_holiday_ajaxHoliday
                if (preg_match('#^/holidays(?:/(?P<month>[^/]++)(?:/(?P<year>[^/]++))?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_holiday_ajaxHoliday;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_holiday_ajaxHoliday')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::ajaxHolidayAction',  'month' => NULL,  'year' => NULL,));
                }
                not_hris_admin_holiday_ajaxHoliday:

            }

        }

        // hris_admin_holiday_ajax_holiday_all
        if ($pathinfo === '/ajax/holidays/all') {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_hris_admin_holiday_ajax_holiday_all;
            }

            return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::ajaxAllHolidayAction',  '_route' => 'hris_admin_holiday_ajax_holiday_all',);
        }
        not_hris_admin_holiday_ajax_holiday_all:

        if (0 === strpos($pathinfo, '/settings/overtime')) {
            // hris_admin_otsetting_index
            if ($pathinfo === '/settings/overtime') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_otsetting_index;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SettingController::overtimeIndexAction',  '_route' => 'hris_admin_otsetting_index',);
            }
            not_hris_admin_otsetting_index:

            // hris_admin_otsetting_submit
            if ($pathinfo === '/settings/overtime') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_otsetting_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\SettingController::overtimeSubmitAction',  '_route' => 'hris_admin_otsetting_submit',);
            }
            not_hris_admin_otsetting_submit:

        }

        if (0 === strpos($pathinfo, '/ajax/holidays')) {
            // hris_admin_holiday_ajax_get_form
            if (preg_match('#^/ajax/holidays/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_holiday_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_holiday_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::ajaxGetFormAction',));
            }
            not_hris_admin_holiday_ajax_get_form:

            // hris_admin_holiday_ajax_add
            if ($pathinfo === '/ajax/holidays') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_holiday_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::ajaxAddAction',  '_route' => 'hris_admin_holiday_ajax_add',);
            }
            not_hris_admin_holiday_ajax_add:

            // hris_admin_holiday_ajax_save
            if (preg_match('#^/ajax/holidays/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_holiday_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_holiday_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\HolidayController::ajaxSaveAction',));
            }
            not_hris_admin_holiday_ajax_save:

        }

        if (0 === strpos($pathinfo, '/leave')) {
            if (0 === strpos($pathinfo, '/leave/type')) {
                // hris_admin_leave_type_index
                if ($pathinfo === '/leave/types') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_leave_type_index;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveTypeController::indexAction',  '_route' => 'hris_admin_leave_type_index',);
                }
                not_hris_admin_leave_type_index:

                // hris_admin_leave_type_add_form
                if ($pathinfo === '/leave/type') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_leave_type_add_form;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveTypeController::addFormAction',  '_route' => 'hris_admin_leave_type_add_form',);
                }
                not_hris_admin_leave_type_add_form:

                // hris_admin_leave_type_add_submit
                if ($pathinfo === '/leave/type') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_admin_leave_type_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveTypeController::addSubmitAction',  '_route' => 'hris_admin_leave_type_add_submit',);
                }
                not_hris_admin_leave_type_add_submit:

                // hris_admin_leave_type_edit_form
                if (preg_match('#^/leave/type/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_leave_type_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_leave_type_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveTypeController::editFormAction',));
                }
                not_hris_admin_leave_type_edit_form:

                // hris_admin_leave_type_edit_submit
                if (preg_match('#^/leave/type/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_admin_leave_type_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_leave_type_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveTypeController::editSubmitAction',));
                }
                not_hris_admin_leave_type_edit_submit:

                // hris_admin_leave_type_delete
                if (preg_match('#^/leave/type/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_leave_type_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_leave_type_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveTypeController::deleteAction',));
                }
                not_hris_admin_leave_type_delete:

                // hris_admin_leave_type_grid
                if ($pathinfo === '/leave/types/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_leave_type_grid;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveTypeController::gridAction',  '_route' => 'hris_admin_leave_type_grid',);
                }
                not_hris_admin_leave_type_grid:

            }

            if (0 === strpos($pathinfo, '/leave/rule')) {
                // hris_admin_leave_rules_index
                if ($pathinfo === '/leave/rules') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_leave_rules_index;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveRulesController::indexAction',  '_route' => 'hris_admin_leave_rules_index',);
                }
                not_hris_admin_leave_rules_index:

                // hris_admin_leave_rules_add_form
                if ($pathinfo === '/leave/rule') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_leave_rules_add_form;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveRulesController::addFormAction',  '_route' => 'hris_admin_leave_rules_add_form',);
                }
                not_hris_admin_leave_rules_add_form:

                // hris_admin_leave_rules_add_submit
                if ($pathinfo === '/leave/rule') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_admin_leave_rules_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveRulesController::addSubmitAction',  '_route' => 'hris_admin_leave_rules_add_submit',);
                }
                not_hris_admin_leave_rules_add_submit:

                // hris_admin_leave_rules_edit_form
                if (preg_match('#^/leave/rule/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_leave_rules_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_leave_rules_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveRulesController::editFormAction',));
                }
                not_hris_admin_leave_rules_edit_form:

                // hris_admin_leave_rules_edit_submit
                if (preg_match('#^/leave/rule/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_admin_leave_rules_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_leave_rules_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveRulesController::editSubmitAction',));
                }
                not_hris_admin_leave_rules_edit_submit:

                // hris_admin_leave_rules_delete
                if (preg_match('#^/leave/rule/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_leave_rules_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_leave_rules_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveRulesController::deleteAction',));
                }
                not_hris_admin_leave_rules_delete:

                // hris_admin_leave_rules_grid
                if ($pathinfo === '/leave/rules/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_admin_leave_rules_grid;
                    }

                    return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\Leave\\LeaveRulesController::gridAction',  '_route' => 'hris_admin_leave_rules_grid',);
                }
                not_hris_admin_leave_rules_grid:

            }

        }

        if (0 === strpos($pathinfo, '/biometrics')) {
            if (0 === strpos($pathinfo, '/biometrics/index')) {
                // hris_biometrics_homepage
                if ($pathinfo === '/biometrics/index') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_biometrics_homepage;
                    }

                    return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\BiometricsController::indexAction',  '_route' => 'hris_biometrics_homepage',);
                }
                not_hris_biometrics_homepage:

                // hris_biometrics_index
                if ($pathinfo === '/biometrics/index') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_biometrics_index;
                    }

                    return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\BiometricsController::indexAction',  '_route' => 'hris_biometrics_index',);
                }
                not_hris_biometrics_index:

                // hris_biometrics_homepage_submit
                if ($pathinfo === '/biometrics/index') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_biometrics_homepage_submit;
                    }

                    return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\BiometricsController::indexSubmitAction',  '_route' => 'hris_biometrics_homepage_submit',);
                }
                not_hris_biometrics_homepage_submit:

            }

            // hris_biometrics_device_data_index
            if ($pathinfo === '/biometrics/device/index') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_biometrics_device_data_index;
                }

                return array (  '_controller' => 'Hris\\BiometricsBundle\\Controller\\DeviceDataController::indexAction',  '_route' => 'hris_biometrics_device_data_index',);
            }
            not_hris_biometrics_device_data_index:

        }

        if (0 === strpos($pathinfo, '/form-code')) {
            // hris_admin_form_codes_index
            if ($pathinfo === '/form-code') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_form_codes_index;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\FormCodesController::indexAction',  '_route' => 'hris_admin_form_codes_index',);
            }
            not_hris_admin_form_codes_index:

            // hris_admin_form_codes_add_submit
            if ($pathinfo === '/form-code') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_form_codes_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\FormCodesController::submitAction',  '_route' => 'hris_admin_form_codes_add_submit',);
            }
            not_hris_admin_form_codes_add_submit:

            // hris_admin_form_codes_edit_form
            if (preg_match('#^/form\\-code/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_form_codes_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_form_codes_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\FormCodesController::editFormAction',));
            }
            not_hris_admin_form_codes_edit_form:

            // hris_admin_form_codes_edit_submit
            if (preg_match('#^/form\\-code/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_form_codes_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_form_codes_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\FormCodesController::editSubmitAction',));
            }
            not_hris_admin_form_codes_edit_submit:

            // hris_admin_form_codes_delete
            if (preg_match('#^/form\\-code/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_form_codes_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_form_codes_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\FormCodesController::deleteAction',));
            }
            not_hris_admin_form_codes_delete:

            // hris_admin_form_codes_grid
            if ($pathinfo === '/form-codes/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_form_codes_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\FormCodesController::gridAction',  '_route' => 'hris_admin_form_codes_grid',);
            }
            not_hris_admin_form_codes_grid:

        }

        // hris_admin_form_codes_ajax_get
        if (0 === strpos($pathinfo, '/ajax/form-code') && preg_match('#^/ajax/form\\-code/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_hris_admin_form_codes_ajax_get;
            }

            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_form_codes_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\FormCodesController::ajaxGetAction',));
        }
        not_hris_admin_form_codes_ajax_get:

        if (0 === strpos($pathinfo, '/clearance')) {
            // hris_admin_clearance_index
            if ($pathinfo === '/clearances') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ExitClearanceController::indexAction',  '_route' => 'hris_admin_clearance_index',);
            }

            // hris_admin_clearance_add_form
            if ($pathinfo === '/clearance') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_clearance_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ExitClearanceController::addFormAction',  '_route' => 'hris_admin_clearance_add_form',);
            }
            not_hris_admin_clearance_add_form:

            // hris_admin_clearance_add_submit
            if ($pathinfo === '/clearance') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_clearance_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ExitClearanceController::addSubmitAction',  '_route' => 'hris_admin_clearance_add_submit',);
            }
            not_hris_admin_clearance_add_submit:

            // hris_admin_clearance_edit_form
            if (preg_match('#^/clearance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_clearance_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_clearance_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ExitClearanceController::editFormAction',));
            }
            not_hris_admin_clearance_edit_form:

            // hris_admin_clearance_edit_submit
            if (preg_match('#^/clearance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_clearance_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_clearance_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ExitClearanceController::editSubmitAction',));
            }
            not_hris_admin_clearance_edit_submit:

            // hris_admin_clearance_delete
            if (preg_match('#^/clearance/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_clearance_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_clearance_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ExitClearanceController::deleteAction',));
            }
            not_hris_admin_clearance_delete:

            // hris_admin_clearance_grid
            if ($pathinfo === '/clearances/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_clearance_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ExitClearanceController::gridAction',  '_route' => 'hris_admin_clearance_grid',);
            }
            not_hris_admin_clearance_grid:

        }

        if (0 === strpos($pathinfo, '/ajax/clearance')) {
            // hris_admin_clearance_ajax_get
            if (preg_match('#^/ajax/clearance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_clearance_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_clearance_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ExitClearanceController::ajaxGetAction',));
            }
            not_hris_admin_clearance_ajax_get:

            // hris_admin_clearance_ajax_get_form
            if (preg_match('#^/ajax/clearance/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_clearance_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_clearance_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ExitClearanceController::ajaxGetFormAction',));
            }
            not_hris_admin_clearance_ajax_get_form:

            // hris_admin_clearance_ajax_add
            if ($pathinfo === '/ajax/clearance') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_clearance_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ExitClearanceController::ajaxAddAction',  '_route' => 'hris_admin_clearance_ajax_add',);
            }
            not_hris_admin_clearance_ajax_add:

            // hris_admin_clearance_ajax_save
            if (preg_match('#^/ajax/clearance/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_clearance_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_clearance_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\ExitClearanceController::ajaxSaveAction',));
            }
            not_hris_admin_clearance_ajax_save:

        }

        if (0 === strpos($pathinfo, '/privilege')) {
            // hris_admin_privilege_index
            if ($pathinfo === '/privileges') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::indexAction',  '_route' => 'hris_admin_privilege_index',);
            }

            // hris_admin_privilege_add_form
            if ($pathinfo === '/privilege') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_privilege_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::addFormAction',  '_route' => 'hris_admin_privilege_add_form',);
            }
            not_hris_admin_privilege_add_form:

            // hris_admin_privilege_add_submit
            if ($pathinfo === '/privilege') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_privilege_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::addSubmitAction',  '_route' => 'hris_admin_privilege_add_submit',);
            }
            not_hris_admin_privilege_add_submit:

            // hris_admin_privilege_edit_form
            if (preg_match('#^/privilege/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_privilege_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_privilege_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::editFormAction',));
            }
            not_hris_admin_privilege_edit_form:

            // hris_admin_privilege_edit_submit
            if (preg_match('#^/privilege/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_privilege_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_privilege_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::editSubmitAction',));
            }
            not_hris_admin_privilege_edit_submit:

            // hris_admin_privilege_delete
            if (preg_match('#^/privilege/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_privilege_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_privilege_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::deleteAction',));
            }
            not_hris_admin_privilege_delete:

            // hris_admin_privilege_grid
            if ($pathinfo === '/privileges/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_privilege_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::gridAction',  '_route' => 'hris_admin_privilege_grid',);
            }
            not_hris_admin_privilege_grid:

        }

        if (0 === strpos($pathinfo, '/ajax/privilege')) {
            // hris_admin_privilege_ajax_get
            if (preg_match('#^/ajax/privilege/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_privilege_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_privilege_ajax_get')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::ajaxGetAction',));
            }
            not_hris_admin_privilege_ajax_get:

            // hris_admin_privilege_ajax_get_form
            if (preg_match('#^/ajax/privilege/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_privilege_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_privilege_ajax_get_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::ajaxGetFormAction',));
            }
            not_hris_admin_privilege_ajax_get_form:

            // hris_admin_privilege_ajax_add
            if ($pathinfo === '/ajax/privilege') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_privilege_ajax_add;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::ajaxAddAction',  '_route' => 'hris_admin_privilege_ajax_add',);
            }
            not_hris_admin_privilege_ajax_add:

            // hris_admin_privilege_ajax_save
            if (preg_match('#^/ajax/privilege/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_privilege_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_privilege_ajax_save')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::ajaxSaveAction',));
            }
            not_hris_admin_privilege_ajax_save:

        }

        // hris_admin_privilege_check
        if ($pathinfo === '/check/privilege') {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_hris_admin_privilege_check;
            }

            return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\PrivilegeController::ajaxCheckAction',  '_route' => 'hris_admin_privilege_check',);
        }
        not_hris_admin_privilege_check:

        if (0 === strpos($pathinfo, '/announcement')) {
            // hris_admin_announcements_index
            if ($pathinfo === '/announcements') {
                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AnnouncementsController::indexAction',  '_route' => 'hris_admin_announcements_index',);
            }

            // hris_admin_announcements_add_form
            if ($pathinfo === '/announcement') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_announcements_add_form;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AnnouncementsController::addFormAction',  '_route' => 'hris_admin_announcements_add_form',);
            }
            not_hris_admin_announcements_add_form:

            // hris_admin_announcements_add_submit
            if ($pathinfo === '/announcement') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_announcements_add_submit;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AnnouncementsController::addSubmitAction',  '_route' => 'hris_admin_announcements_add_submit',);
            }
            not_hris_admin_announcements_add_submit:

            // hris_admin_announcements_edit_form
            if (preg_match('#^/announcement/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_announcements_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_announcements_edit_form')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AnnouncementsController::editFormAction',));
            }
            not_hris_admin_announcements_edit_form:

            // hris_admin_announcements_edit_submit
            if (preg_match('#^/announcement/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_admin_announcements_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_announcements_edit_submit')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AnnouncementsController::editSubmitAction',));
            }
            not_hris_admin_announcements_edit_submit:

            // hris_admin_announcements_delete
            if (preg_match('#^/announcement/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_announcements_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_admin_announcements_delete')), array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AnnouncementsController::deleteAction',));
            }
            not_hris_admin_announcements_delete:

            // hris_admin_announcements_grid
            if ($pathinfo === '/announcements/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_admin_announcements_grid;
                }

                return array (  '_controller' => 'Hris\\AdminBundle\\Controller\\AnnouncementsController::gridAction',  '_route' => 'hris_admin_announcements_grid',);
            }
            not_hris_admin_announcements_grid:

        }

        if (0 === strpos($pathinfo, '/reports')) {
            // hris_report_homepage
            if (0 === strpos($pathinfo, '/reports/hello') && preg_match('#^/reports/hello/(?P<name>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_homepage')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\DefaultController::indexAction',));
            }

            if (0 === strpos($pathinfo, '/reports/attendance')) {
                // hris_report_attendance_index
                if ($pathinfo === '/reports/attendance') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_attendance_index;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\AttendanceReportController::indexAction',  '_route' => 'hris_report_attendance_index',);
                }
                not_hris_report_attendance_index:

                // hris_report_attendance_grid
                if ($pathinfo === '/reports/attendance/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_attendance_grid;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\AttendanceReportController::gridAction',  '_route' => 'hris_report_attendance_grid',);
                }
                not_hris_report_attendance_grid:

                // hris_report_attendance_print
                if (0 === strpos($pathinfo, '/reports/attendance/print') && preg_match('#^/reports/attendance/print(?:/(?P<id>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++)(?:/(?P<department>[^/]++)(?:/(?P<position>[^/]++)(?:/(?P<rank>[^/]++)(?:/(?P<gender>[^/]++)(?:/(?P<days>[^/]++)(?:/(?P<late>[^/]++)(?:/(?P<late_to>[^/]++)(?:/(?P<undertime>[^/]++)(?:/(?P<undertime_to>[^/]++)(?:/(?P<status>[^/]++)(?:/(?P<location>[^/]++))?)?)?)?)?)?)?)?)?)?)?)?)?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_attendance_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_attendance_print')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\AttendanceReportController::printAction',  'id' => NULL,  'date_from' => NULL,  'date_to' => NULL,  'department' => NULL,  'position' => NULL,  'rank' => NULL,  'gender' => NULL,  'days' => NULL,  'late' => NULL,  'late_to' => NULL,  'undertime' => NULL,  'undertime_to' => NULL,  'status' => NULL,  'location' => NULL,));
                }
                not_hris_report_attendance_print:

                // hris_report_attendance_csv
                if (0 === strpos($pathinfo, '/reports/attendance/csv') && preg_match('#^/reports/attendance/csv(?:/(?P<id>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++)(?:/(?P<department>[^/]++)(?:/(?P<position>[^/]++)(?:/(?P<rank>[^/]++)(?:/(?P<gender>[^/]++)(?:/(?P<days>[^/]++)(?:/(?P<late>[^/]++)(?:/(?P<late_to>[^/]++)(?:/(?P<undertime>[^/]++)(?:/(?P<undertime_to>[^/]++)(?:/(?P<status>[^/]++)(?:/(?P<location>[^/]++))?)?)?)?)?)?)?)?)?)?)?)?)?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_attendance_csv;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_attendance_csv')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\AttendanceReportController::csvAction',  'id' => NULL,  'date_from' => NULL,  'date_to' => NULL,  'department' => NULL,  'position' => NULL,  'rank' => NULL,  'gender' => NULL,  'days' => NULL,  'late' => NULL,  'late_to' => NULL,  'undertime' => NULL,  'undertime_to' => NULL,  'status' => NULL,  'location' => NULL,));
                }
                not_hris_report_attendance_csv:

                // hris_report_attendance_view
                if ($pathinfo === '/reports/attendance/view') {
                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\AttendanceReportController::viewAction',  '_route' => 'hris_report_attendance_view',);
                }

                // hris_report_attendance_ajax_grid
                if (0 === strpos($pathinfo, '/reports/attendances/ajax') && preg_match('#^/reports/attendances/ajax(?:/(?P<id>[^/]++)(?:/(?P<department>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++)(?:/(?P<position>[^/]++)(?:/(?P<rank>[^/]++)(?:/(?P<gender>[^/]++)(?:/(?P<days>[^/]++)(?:/(?P<late>[^/]++)(?:/(?P<late_to>[^/]++)(?:/(?P<undertime>[^/]++)(?:/(?P<undertime_to>[^/]++)(?:/(?P<status>[^/]++)(?:/(?P<location>[^/]++))?)?)?)?)?)?)?)?)?)?)?)?)?)?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_attendance_ajax_grid')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\AttendanceReportController::gridAttendancesReportAction',  'id' => NULL,  'department' => NULL,  'date_from' => NULL,  'date_to' => NULL,  'position' => NULL,  'rank' => NULL,  'gender' => NULL,  'days' => NULL,  'late' => NULL,  'late_to' => NULL,  'undertime' => NULL,  'undertime_to' => NULL,  'status' => NULL,  'location' => NULL,));
                }

                // hris_report_attendance_ajax
                if (preg_match('#^/reports/attendance(?:/(?P<id>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++)(?:/(?P<position>[^/]++)(?:/(?P<rank>[^/]++)(?:/(?P<gender>[^/]++)(?:/(?P<days>[^/]++)(?:/(?P<late>[^/]++)(?:/(?P<late_to>[^/]++)(?:/(?P<undertime>[^/]++)(?:/(?P<undertime_to>[^/]++)(?:/(?P<status>[^/]++)(?:/(?P<location>[^/]++))?)?)?)?)?)?)?)?)?)?)?)?)?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_attendance_ajax')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\AttendanceReportController::gridAttendanceReportAction',  'id' => NULL,  'department' => NULL,  'date_from' => NULL,  'date_to' => NULL,  'position' => NULL,  'rank' => NULL,  'gender' => NULL,  'days' => NULL,  'late' => NULL,  'late_to' => NULL,  'undertime' => NULL,  'undertime_to' => NULL,  'status' => NULL,  'location' => NULL,));
                }

            }

            if (0 === strpos($pathinfo, '/reports/regulars')) {
                // hris_report_regulars_index
                if ($pathinfo === '/reports/regulars') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_regulars_index;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\RegularsReportController::indexAction',  '_route' => 'hris_report_regulars_index',);
                }
                not_hris_report_regulars_index:

                // hris_report_regulars_grid
                if ($pathinfo === '/reports/regulars/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_regulars_grid;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\RegularsReportController::gridAction',  '_route' => 'hris_report_regulars_grid',);
                }
                not_hris_report_regulars_grid:

                // hris_report_regulars_print
                if (0 === strpos($pathinfo, '/reports/regulars/print') && preg_match('#^/reports/regulars/print(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_regulars_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_regulars_print')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\RegularsReportController::printAction',  'id' => NULL,));
                }
                not_hris_report_regulars_print:

                // hris_report_regulars_csv
                if (0 === strpos($pathinfo, '/reports/regulars/csv') && preg_match('#^/reports/regulars/csv(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_regulars_csv;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_regulars_csv')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\RegularsReportController::csvAction',  'id' => NULL,));
                }
                not_hris_report_regulars_csv:

                // hris_report_regulars_view
                if ($pathinfo === '/reports/regulars/view') {
                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\RegularsReportController::viewAction',  '_route' => 'hris_report_regulars_view',);
                }

                // hris_report_regulars_ajax_grid
                if (0 === strpos($pathinfo, '/reports/regulars/ajax') && preg_match('#^/reports/regulars/ajax(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_regulars_ajax_grid')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\RegularsReportController::gridRegularsReportAction',  'id' => NULL,));
                }

                // hris_report_regulars_ajax
                if (preg_match('#^/reports/regulars(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_regulars_ajax')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\RegularsReportController::gridRegularReportAction',  'id' => NULL,));
                }

            }

            if (0 === strpos($pathinfo, '/reports/totalexpense')) {
                // hris_report_total_expense_index
                if ($pathinfo === '/reports/totalexpense') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_total_expense_index;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TotalExpenseReportController::indexAction',  '_route' => 'hris_report_total_expense_index',);
                }
                not_hris_report_total_expense_index:

                // hris_report_total_expense_grid
                if ($pathinfo === '/reports/totalexpense/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_total_expense_grid;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TotalExpenseReportController::gridAction',  '_route' => 'hris_report_total_expense_grid',);
                }
                not_hris_report_total_expense_grid:

                // hris_report_total_expense_print
                if (0 === strpos($pathinfo, '/reports/totalexpense/print') && preg_match('#^/reports/totalexpense/print(?:/(?P<month>[^/]++)(?:/(?P<year>[^/]++))?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_total_expense_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_total_expense_print')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TotalExpenseReportController::printAction',  'month' => NULL,  'year' => NULL,));
                }
                not_hris_report_total_expense_print:

                // hris_report_total_expense_csv
                if (0 === strpos($pathinfo, '/reports/totalexpense/csv') && preg_match('#^/reports/totalexpense/csv(?:/(?P<month>[^/]++)(?:/(?P<year>[^/]++))?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_total_expense_csv;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_total_expense_csv')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TotalExpenseReportController::csvAction',  'month' => NULL,  'year' => NULL,));
                }
                not_hris_report_total_expense_csv:

                // hris_report_total_expense_view
                if ($pathinfo === '/reports/totalexpense/view') {
                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TotalExpenseReportController::viewAction',  '_route' => 'hris_report_total_expense_view',);
                }

                // hris_report_total_expense_ajax_grid
                if (0 === strpos($pathinfo, '/reports/totalexpense/ajax') && preg_match('#^/reports/totalexpense/ajax(?:/(?P<month>[^/]++)(?:/(?P<year>[^/]++))?)?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_total_expense_ajax_grid')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TotalExpenseReportController::gridTotalExpenseReportAction',  'month' => NULL,  'year' => NULL,));
                }

                // hris_report_total_expense_ajax
                if (preg_match('#^/reports/totalexpense(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_total_expense_ajax')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TotalExpenseReportController::gridRegularReportAction',  'id' => NULL,));
                }

                // hris_report_total_expense_update_total
                if (0 === strpos($pathinfo, '/reports/totalexpense/grid/update') && preg_match('#^/reports/totalexpense/grid/update(?:/(?P<month>[^/]++)(?:/(?P<year>[^/]++))?)?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_total_expense_update_total')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TotalExpenseReportController::updateTotalAction',  'month' => NULL,  'year' => NULL,));
                }

            }

            if (0 === strpos($pathinfo, '/reports/reimbursements')) {
                // hris_report_reimbursement_index
                if ($pathinfo === '/reports/reimbursements') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_reimbursement_index;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\ReimbursementReportController::indexAction',  '_route' => 'hris_report_reimbursement_index',);
                }
                not_hris_report_reimbursement_index:

                // hris_report_reimbursement_grid
                if ($pathinfo === '/reports/reimbursements/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_reimbursement_grid;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\ReimbursementReportController::gridReimbursementAction',  '_route' => 'hris_report_reimbursement_grid',);
                }
                not_hris_report_reimbursement_grid:

                // hris_report_reimbursement_print
                if (0 === strpos($pathinfo, '/reports/reimbursements/print') && preg_match('#^/reports/reimbursements/print(?:/(?P<department>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++)(?:/(?P<status>[^/]++))?)?)?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_reimbursement_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_reimbursement_print')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\ReimbursementReportController::printAction',  'department' => NULL,  'date_from' => NULL,  'date_to' => NULL,  'status' => NULL,));
                }
                not_hris_report_reimbursement_print:

                // hris_report_reimbursement_export
                if (0 === strpos($pathinfo, '/reports/reimbursements/export') && preg_match('#^/reports/reimbursements/export(?:/(?P<department>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++)(?:/(?P<status>[^/]++))?)?)?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_reimbursement_export;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_reimbursement_export')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\ReimbursementReportController::reimbursementCSVAction',  'department' => NULL,  'date_from' => NULL,  'date_to' => NULL,  'status' => NULL,));
                }
                not_hris_report_reimbursement_export:

            }

            if (0 === strpos($pathinfo, '/reports/turnovers')) {
                // hris_report_turnover_index
                if ($pathinfo === '/reports/turnovers') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_turnover_index;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TurnoverReportController::indexAction',  '_route' => 'hris_report_turnover_index',);
                }
                not_hris_report_turnover_index:

                // hris_report_turnover_grid
                if (0 === strpos($pathinfo, '/reports/turnovers/grid') && preg_match('#^/reports/turnovers/grid(?:/(?P<department>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_turnover_grid;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_turnover_grid')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TurnoverReportController::gridTurnoverAction',  'department' => NULL,  'date_from' => NULL,  'date_to' => NULL,));
                }
                not_hris_report_turnover_grid:

                // hris_report_turnover_print
                if (0 === strpos($pathinfo, '/reports/turnovers/print') && preg_match('#^/reports/turnovers/print(?:/(?P<department>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_turnover_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_turnover_print')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TurnoverReportController::printAction',  'department' => NULL,  'date_from' => NULL,  'date_to' => NULL,));
                }
                not_hris_report_turnover_print:

                // hris_report_turnover_export
                if (0 === strpos($pathinfo, '/reports/turnovers/export') && preg_match('#^/reports/turnovers/export(?:/(?P<department>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_turnover_export;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_turnover_export')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\TurnoverReportController::turnoverCSVAction',  'department' => NULL,  'date_from' => NULL,  'date_to' => NULL,));
                }
                not_hris_report_turnover_export:

            }

            if (0 === strpos($pathinfo, '/reports/loans')) {
                // hris_report_loans_index
                if ($pathinfo === '/reports/loans') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_loans_index;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\LoanReportController::indexAction',  '_route' => 'hris_report_loans_index',);
                }
                not_hris_report_loans_index:

                // hris_report_loans_grid
                if ($pathinfo === '/reports/loans/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_loans_grid;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\LoanReportController::gridAction',  '_route' => 'hris_report_loans_grid',);
                }
                not_hris_report_loans_grid:

                // hris_report_loans_submit
                if ($pathinfo === '/reports/loans') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_report_loans_submit;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\LoanReportController::submitAction',  '_route' => 'hris_report_loans_submit',);
                }
                not_hris_report_loans_submit:

            }

            // hris_report_loans_ajax_get
            if (0 === strpos($pathinfo, '/reports/ajax/loans') && preg_match('#^/reports/ajax/loans/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_report_loans_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_loans_ajax_get')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\LoanReportController::ajaxGetAction',));
            }
            not_hris_report_loans_ajax_get:

            if (0 === strpos($pathinfo, '/reports/loan')) {
                // hris_report_loans_status
                if (preg_match('#^/reports/loan/(?P<id>[^/]++)/status/(?P<status>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_loans_status;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_loans_status')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\LoanReportController::statusUpdateAction',));
                }
                not_hris_report_loans_status:

                // hris_report_loans_ajax_grid
                if (0 === strpos($pathinfo, '/reports/loans/ajax') && preg_match('#^/reports/loans/ajax/(?P<id>[^/]++)/(?P<date_from>[^/]++)/(?P<date_to>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_loans_ajax_grid')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\LoanReportController::gridIncentiveAction',));
                }

            }

            // hris_report_loans_ajax_filter_get
            if ($pathinfo === '/reports/ajax/filter/loans') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_report_loans_ajax_filter_get;
                }

                return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\LoanReportController::ajaxFilterGetAction',  '_route' => 'hris_report_loans_ajax_filter_get',);
            }
            not_hris_report_loans_ajax_filter_get:

            // hris_report_loans_print
            if ($pathinfo === '/reports/loans/print') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_report_loans_print;
                }

                return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\LoanReportController::printAction',  '_route' => 'hris_report_loans_print',);
            }
            not_hris_report_loans_print:

            if (0 === strpos($pathinfo, '/reports/incentives')) {
                // hris_report_incentives_index
                if ($pathinfo === '/reports/incentives') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_incentives_index;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\IncentiveReportController::indexAction',  '_route' => 'hris_report_incentives_index',);
                }
                not_hris_report_incentives_index:

                // hris_report_incentives_grid
                if ($pathinfo === '/reports/incentives/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_incentives_grid;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\IncentiveReportController::gridAction',  '_route' => 'hris_report_incentives_grid',);
                }
                not_hris_report_incentives_grid:

                // hris_report_incentives_submit
                if ($pathinfo === '/reports/incentives') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_report_incentives_submit;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\IncentiveReportController::submitAction',  '_route' => 'hris_report_incentives_submit',);
                }
                not_hris_report_incentives_submit:

            }

            // hris_report_incentives_ajax_get
            if (0 === strpos($pathinfo, '/reports/ajax/incentives') && preg_match('#^/reports/ajax/incentives/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_report_incentives_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_incentives_ajax_get')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\IncentiveReportController::ajaxGetAction',));
            }
            not_hris_report_incentives_ajax_get:

            if (0 === strpos($pathinfo, '/reports/incentive')) {
                // hris_report_incentives_status
                if (preg_match('#^/reports/incentive/(?P<id>[^/]++)/status/(?P<status>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_incentives_status;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_incentives_status')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\IncentiveReportController::statusUpdateAction',));
                }
                not_hris_report_incentives_status:

                // hris_report_incentives_ajax_grid
                if (0 === strpos($pathinfo, '/reports/incentives/ajax') && preg_match('#^/reports/incentives/ajax/(?P<id>[^/]++)/(?P<date_from>[^/]++)/(?P<date_to>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_incentives_ajax_grid')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\IncentiveReportController::gridIncentiveAction',));
                }

            }

            // hris_report_incentives_ajax_filter_get
            if ($pathinfo === '/reports/ajax/filter/incentives') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_report_incentives_ajax_filter_get;
                }

                return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\IncentiveReportController::ajaxFilterGetAction',  '_route' => 'hris_report_incentives_ajax_filter_get',);
            }
            not_hris_report_incentives_ajax_filter_get:

            // hris_report_incentives_print
            if ($pathinfo === '/reports/incentives/print') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_report_incentives_print;
                }

                return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\IncentiveReportController::printAction',  '_route' => 'hris_report_incentives_print',);
            }
            not_hris_report_incentives_print:

            if (0 === strpos($pathinfo, '/reports/evaluation')) {
                if (0 === strpos($pathinfo, '/reports/evaluations')) {
                    // hris_report_evals_index
                    if ($pathinfo === '/reports/evaluations') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_report_evals_index;
                        }

                        return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\EvaluationReportController::indexAction',  '_route' => 'hris_report_evals_index',);
                    }
                    not_hris_report_evals_index:

                    // hris_report_evals_grid
                    if ($pathinfo === '/reports/evaluations/grid') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_report_evals_grid;
                        }

                        return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\EvaluationReportController::gridEvalAction',  '_route' => 'hris_report_evals_grid',);
                    }
                    not_hris_report_evals_grid:

                }

                // hris_report_evals_print
                if (0 === strpos($pathinfo, '/reports/evaluation/print') && preg_match('#^/reports/evaluation/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_evals_print')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\EvaluationReportController::printEvalAction',));
                }

                // hris_report_evals_print_all
                if ($pathinfo === '/reports/evaluations/print/all') {
                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\EvaluationReportController::printAllAction',  '_route' => 'hris_report_evals_print_all',);
                }

            }

            if (0 === strpos($pathinfo, '/reports/leave')) {
                if (0 === strpos($pathinfo, '/reports/leaves')) {
                    // hris_report_leave_index
                    if ($pathinfo === '/reports/leaves') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_report_leave_index;
                        }

                        return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\LeavesReportController::indexAction',  '_route' => 'hris_report_leave_index',);
                    }
                    not_hris_report_leave_index:

                    // hris_report_leave_grid
                    if ($pathinfo === '/reports/leaves/grid') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_report_leave_grid;
                        }

                        return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\LeavesReportController::gridLeaveAction',  '_route' => 'hris_report_leave_grid',);
                    }
                    not_hris_report_leave_grid:

                }

                // hris_report_leave_print
                if ($pathinfo === '/reports/leave/print') {
                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\LeavesReportController::printLeaveAction',  '_route' => 'hris_report_leave_print',);
                }

            }

            if (0 === strpos($pathinfo, '/reports/payroll')) {
                // hris_report_payroll_index
                if ($pathinfo === '/reports/payroll') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_report_payroll_index;
                    }

                    return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\PayrollReportController::indexAction',  '_route' => 'hris_report_payroll_index',);
                }
                not_hris_report_payroll_index:

                if (0 === strpos($pathinfo, '/reports/payroll/print')) {
                    // hris_report_payroll_print
                    if ($pathinfo === '/reports/payroll/print') {
                        return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\PayrollReportController::printPayAction',  '_route' => 'hris_report_payroll_print',);
                    }

                    if (0 === strpos($pathinfo, '/reports/payroll/print_')) {
                        // hris_report_payroll_print_rep
                        if (0 === strpos($pathinfo, '/reports/payroll/print_rep') && preg_match('#^/reports/payroll/print_rep(?:/(?P<period>[^/]++))?$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_payroll_print_rep')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\PayrollReportController::printReportAction',  'period' => -1,));
                        }

                        // hris_report_payroll_print_payslips
                        if (0 === strpos($pathinfo, '/reports/payroll/print_payslips') && preg_match('#^/reports/payroll/print_payslips/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_report_payroll_print_payslips;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_payroll_print_payslips')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\PayrollReportController::printPayslipsAction',));
                        }
                        not_hris_report_payroll_print_payslips:

                    }

                }

                // hris_report_payroll_grid
                if (0 === strpos($pathinfo, '/reports/payroll/grid') && preg_match('#^/reports/payroll/grid(?:/(?P<id>[^/]++)(?:/(?P<department>[^/]++)(?:/(?P<period_id>[^/]++)(?:/(?P<pay_type>[^/]++))?)?)?)?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_report_payroll_grid')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\PayrollReportController::gridPayrollReportAction',  'id' => NULL,  'department' => NULL,  'period_id' => NULL,  'pay_type' => NULL,));
                }

            }

            if (0 === strpos($pathinfo, '/reports/gov')) {
                if (0 === strpos($pathinfo, '/reports/governmentForms/sevenOne')) {
                    // hris_government_forms_seven_index
                    if ($pathinfo === '/reports/governmentForms/sevenOne') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_government_forms_seven_index;
                        }

                        return array (  '_controller' => 'Quadrant\\ReportBundle\\Controller\\GovernmentFormsReportController::indexAction',  '_route' => 'hris_government_forms_seven_index',);
                    }
                    not_hris_government_forms_seven_index:

                    // hris_government_forms_seven_submit
                    if ($pathinfo === '/reports/governmentForms/sevenOne') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_government_forms_seven_submit;
                        }

                        return array (  '_controller' => 'Quadrant\\ReportBundle\\Controller\\GovernmentFormsReportController::generateAction',  '_route' => 'hris_government_forms_seven_submit',);
                    }
                    not_hris_government_forms_seven_submit:

                    // hris_government_forms_seven_csv
                    if ($pathinfo === '/reports/governmentForms/sevenOne/csv') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_government_forms_seven_csv;
                        }

                        return array (  '_controller' => 'Quadrant\\ReportBundle\\Controller\\GovernmentFormsReportController::csvAction',  '_route' => 'hris_government_forms_seven_csv',);
                    }
                    not_hris_government_forms_seven_csv:

                    // hris_government_forms_seven_get_filters
                    if (0 === strpos($pathinfo, '/reports/governmentForms/sevenOne/filters') && preg_match('#^/reports/governmentForms/sevenOne/filters/(?P<report_id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_government_forms_seven_get_filters;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_forms_seven_get_filters')), array (  '_controller' => 'Quadrant\\ReportBundle\\Controller\\GovernmentFormsReportController::getReportFiltersAction',));
                    }
                    not_hris_government_forms_seven_get_filters:

                }

                if (0 === strpos($pathinfo, '/reports/govt_reports')) {
                    // hris_government_reports_index
                    if ($pathinfo === '/reports/govt_reports') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_government_reports_index;
                        }

                        return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\GovernmentReportsController::indexAction',  '_route' => 'hris_government_reports_index',);
                    }
                    not_hris_government_reports_index:

                    if (0 === strpos($pathinfo, '/reports/govt_reports/ajax/ge')) {
                        if (0 === strpos($pathinfo, '/reports/govt_reports/ajax/get')) {
                            // hris_government_reports_ajax_get_forms
                            if (0 === strpos($pathinfo, '/reports/govt_reports/ajax/get/forms') && preg_match('#^/reports/govt_reports/ajax/get/forms(?:/(?P<type>[^/]++))?$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_government_reports_ajax_get_forms;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_reports_ajax_get_forms')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\GovernmentReportsController::ajaxGetFormsAction',  'type' => NULL,));
                            }
                            not_hris_government_reports_ajax_get_forms:

                            // hris_government_reports_ajax_get_employees
                            if (0 === strpos($pathinfo, '/reports/govt_reports/ajax/get/employees') && preg_match('#^/reports/govt_reports/ajax/get/employees(?:/(?P<dept>[^/]++))?$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_government_reports_ajax_get_employees;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_reports_ajax_get_employees')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\GovernmentReportsController::ajaxGetEmployeesAction',  'dept' => NULL,));
                            }
                            not_hris_government_reports_ajax_get_employees:

                        }

                        // hris_government_reports_ajax_generate
                        if (0 === strpos($pathinfo, '/reports/govt_reports/ajax/generate') && preg_match('#^/reports/govt_reports/ajax/generate(?:/(?P<form>[^/]++)(?:/(?P<year>[^/]++)(?:/(?P<month>[^/]++)(?:/(?P<employee>[^/]++)(?:/(?P<department>[^/]++))?)?)?)?)?$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_government_reports_ajax_generate;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_reports_ajax_generate')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\GovernmentReportsController::ajaxGenerateAction',  'form' => NULL,  'year' => NULL,  'month' => NULL,  'employee' => NULL,  'department' => NULL,));
                        }
                        not_hris_government_reports_ajax_generate:

                    }

                    // hris_government_reports_export
                    if (0 === strpos($pathinfo, '/reports/govt_reports/export') && preg_match('#^/reports/govt_reports/export(?:/(?P<form>[^/]++)(?:/(?P<year>[^/]++)(?:/(?P<month>[^/]++)(?:/(?P<employee>[^/]++)(?:/(?P<department>[^/]++))?)?)?)?)?$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_government_reports_export;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_government_reports_export')), array (  '_controller' => 'Hris\\ReportBundle\\Controller\\GovernmentReportsController::exportAction',  'form' => NULL,  'year' => NULL,  'month' => NULL,  'employee' => NULL,  'department' => NULL,));
                    }
                    not_hris_government_reports_export:

                    // hris_government_reports_ajax_save
                    if ($pathinfo === '/reports/govt_reports/ajax/save/') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_government_reports_ajax_save;
                        }

                        return array (  '_controller' => 'Hris\\ReportBundle\\Controller\\GovernmentReportsController::ajaxSaveAction',  '_route' => 'hris_government_reports_ajax_save',);
                    }
                    not_hris_government_reports_ajax_save:

                }

            }

        }

        if (0 === strpos($pathinfo, '/profile')) {
            if (0 === strpos($pathinfo, '/profile/myProfile')) {
                // hris_profile_employee_index
                if ($pathinfo === '/profile/myProfile') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_employee_index;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\EmployeeController::indexAction',  '_route' => 'hris_profile_employee_index',);
                }
                not_hris_profile_employee_index:

                // hris_profile_employee_edit
                if ($pathinfo === '/profile/myProfile') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_profile_employee_edit;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\EmployeeController::editInfoAction',  '_route' => 'hris_profile_employee_edit',);
                }
                not_hris_profile_employee_edit:

            }

            if (0 === strpos($pathinfo, '/profile/leave')) {
                // hris_profile_leave_index
                if ($pathinfo === '/profile/leaves') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_leave_index;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LeaveController::indexAction',  '_route' => 'hris_profile_leave_index',);
                }
                not_hris_profile_leave_index:

                // hris_profile_leave_add_form
                if ($pathinfo === '/profile/leave') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_leave_add_form;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LeaveController::addFormAction',  '_route' => 'hris_profile_leave_add_form',);
                }
                not_hris_profile_leave_add_form:

                // hris_profile_leave_add_submit
                if ($pathinfo === '/profile/leave') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_profile_leave_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LeaveController::addSubmitAction',  '_route' => 'hris_profile_leave_add_submit',);
                }
                not_hris_profile_leave_add_submit:

                // hris_profile_leave_edit_form
                if (preg_match('#^/profile/leave/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_leave_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_leave_edit_form')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LeaveController::editFormAction',));
                }
                not_hris_profile_leave_edit_form:

                // hris_profile_leave_edit_submit
                if (preg_match('#^/profile/leave/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_profile_leave_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_leave_edit_submit')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LeaveController::editSubmitAction',));
                }
                not_hris_profile_leave_edit_submit:

                // hris_profile_leave_delete
                if (preg_match('#^/profile/leave/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_leave_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_leave_delete')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LeaveController::deleteAction',));
                }
                not_hris_profile_leave_delete:

                // hris_profile_leave_grid
                if ($pathinfo === '/profile/leaves/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_leave_grid;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LeaveController::gridAction',  '_route' => 'hris_profile_leave_grid',);
                }
                not_hris_profile_leave_grid:

            }

            if (0 === strpos($pathinfo, '/profile/ajax')) {
                if (0 === strpos($pathinfo, '/profile/ajax/leaves')) {
                    // hris_profile_leave_ajax_get
                    if (preg_match('#^/profile/ajax/leaves/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_profile_leave_ajax_get;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_leave_ajax_get')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LeaveController::ajaxGetAction',));
                    }
                    not_hris_profile_leave_ajax_get:

                    // hris_profile_leave_ajax_add
                    if ($pathinfo === '/profile/ajax/leaves/add') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_profile_leave_ajax_add;
                        }

                        return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LeaveController::ajaxAddAction',  '_route' => 'hris_profile_leave_ajax_add',);
                    }
                    not_hris_profile_leave_ajax_add:

                }

                if (0 === strpos($pathinfo, '/profile/ajax/emp')) {
                    if (0 === strpos($pathinfo, '/profile/ajax/emp/leave')) {
                        // hris_profile_leave_emp_ajax
                        if (preg_match('#^/profile/ajax/emp/leave/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_profile_leave_emp_ajax;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_leave_emp_ajax')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LeaveController::ajaxGetLeaveAction',));
                        }
                        not_hris_profile_leave_emp_ajax:

                        // hris_profile_leaves_emp_ajax
                        if (0 === strpos($pathinfo, '/profile/ajax/emp/leaves') && preg_match('#^/profile/ajax/emp/leaves/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_profile_leaves_emp_ajax;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_leaves_emp_ajax')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LeaveController::ajaxGetEmpLeaveAction',));
                        }
                        not_hris_profile_leaves_emp_ajax:

                    }

                    // hris_profile_leave_emp_workweek
                    if (0 === strpos($pathinfo, '/profile/ajax/emp/workweek') && preg_match('#^/profile/ajax/emp/workweek/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_leave_emp_workweek')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\EmployeeController::ajaxEmpWorkdaysAction',));
                    }

                }

            }

            if (0 === strpos($pathinfo, '/profile/request')) {
                // hris_profile_request_index
                if ($pathinfo === '/profile/requests') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_request_index;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::indexAction',  '_route' => 'hris_profile_request_index',);
                }
                not_hris_profile_request_index:

                // hris_profile_request_add_form
                if (preg_match('#^/profile/request(?:/(?P<type>[^/]++))?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_request_add_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_request_add_form')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::createFormAction',  'type' => NULL,));
                }
                not_hris_profile_request_add_form:

                // hris_profile_request_add_submit
                if (preg_match('#^/profile/request(?:/(?P<type>[^/]++))?$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_profile_request_add_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_request_add_submit')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::requestSubmitAction',  'type' => NULL,));
                }
                not_hris_profile_request_add_submit:

                // hris_profile_request_edit_form
                if (preg_match('#^/profile/request/(?P<id>[^/]++)/(?P<type>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_request_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_request_edit_form')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::viewFormAction',));
                }
                not_hris_profile_request_edit_form:

                // hris_profile_request_edit_submit
                if (preg_match('#^/profile/request/(?P<id>[^/]++)/(?P<type>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_profile_request_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_request_edit_submit')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::viewSubmitAction',));
                }
                not_hris_profile_request_edit_submit:

                // hris_profile_request_status
                if (preg_match('#^/profile/request/(?P<id>[^/]++)/(?P<type>[^/]++)/(?P<status>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_request_status')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::updateStatusAction',));
                }

            }

            // hris_profile_request_delete
            if (0 === strpos($pathinfo, '/profile/delete/request') && preg_match('#^/profile/delete/request/(?P<id>[^/]++)/(?P<type>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_profile_request_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_request_delete')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::deleteAction',));
            }
            not_hris_profile_request_delete:

            // hris_profile_request_grid
            if ($pathinfo === '/profile/requests/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_profile_request_grid;
                }

                return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::gridAction',  '_route' => 'hris_profile_request_grid',);
            }
            not_hris_profile_request_grid:

            if (0 === strpos($pathinfo, '/profile/ajax/requests')) {
                // hris_profile_request_ajax_get
                if (preg_match('#^/profile/ajax/requests/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_request_ajax_get;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_request_ajax_get')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::ajaxGetAction',));
                }
                not_hris_profile_request_ajax_get:

                // hris_profile_request_ajax_add
                if ($pathinfo === '/profile/ajax/requests/add') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_profile_request_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::ajaxAddAction',  '_route' => 'hris_profile_request_ajax_add',);
                }
                not_hris_profile_request_ajax_add:

            }

            // hris_profile_request_print
            if (0 === strpos($pathinfo, '/profile/print/request') && preg_match('#^/profile/print/request/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_request_print')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\RequestController::printFormAction',));
            }

            if (0 === strpos($pathinfo, '/profile/attendances')) {
                // hris_profile_attendance_index
                if ($pathinfo === '/profile/attendances') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_attendance_index;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\AdjustmentController::indexAction',  '_route' => 'hris_profile_attendance_index',);
                }
                not_hris_profile_attendance_index:

                // hris_profile_attendance_check
                if (0 === strpos($pathinfo, '/profile/attendances/check') && preg_match('#^/profile/attendances/check(?:/(?P<id>[^/]++)(?:/(?P<date>[^/]++))?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_attendance_check;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_attendance_check')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\AdjustmentController::checkAttendanceAction',  'id' => NULL,  'date' => NULL,));
                }
                not_hris_profile_attendance_check:

                // hris_profile_attendance_ajax_add
                if (0 === strpos($pathinfo, '/profile/attendances/ajax/add') && preg_match('#^/profile/attendances/ajax/add(?:/(?P<id>[^/]++)(?:/(?P<date>[^/]++))?)?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_attendance_ajax_add')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\AdjustmentController::adjustmentAddAction',  'id' => NULL,  'date' => NULL,));
                }

                // hris_profile_attendance_overtime
                if (0 === strpos($pathinfo, '/profile/attendances/ot') && preg_match('#^/profile/attendances/ot(?:/(?P<id>[^/]++)(?:/(?P<date>[^/]++))?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_attendance_overtime;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_attendance_overtime')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\AdjustmentController::checkOvertimeAction',  'id' => NULL,  'date' => NULL,));
                }
                not_hris_profile_attendance_overtime:

                // hris_profile_attendance_check_request
                if (0 === strpos($pathinfo, '/profile/attendances/request') && preg_match('#^/profile/attendances/request(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_attendance_check_request')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\AdjustmentController::checkRequestAction',  'id' => NULL,));
                }

            }

            // hris_profile_payroll_details_print
            if (0 === strpos($pathinfo, '/profile/myProfile') && preg_match('#^/profile/myProfile/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_profile_payroll_details_print;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_payroll_details_print')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\EmployeeController::detailsAction',));
            }
            not_hris_profile_payroll_details_print:

            if (0 === strpos($pathinfo, '/profile/trainings')) {
                // hris_profile_training_index
                if ($pathinfo === '/profile/trainings') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_training_index;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\TrainingController::indexAction',  '_route' => 'hris_profile_training_index',);
                }
                not_hris_profile_training_index:

                // hris_profile_training_grid
                if ($pathinfo === '/profile/trainings/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_training_grid;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\TrainingController::gridAction',  '_route' => 'hris_profile_training_grid',);
                }
                not_hris_profile_training_grid:

                // hris_profile_training_search
                if ($pathinfo === '/profile/trainings/search') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_training_search;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\TrainingController::searchAction',  '_route' => 'hris_profile_training_search',);
                }
                not_hris_profile_training_search:

                if (0 === strpos($pathinfo, '/profile/trainings/course')) {
                    // hris_profile_training_course
                    if (preg_match('#^/profile/trainings/course/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_profile_training_course;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_training_course')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\TrainingController::courseAction',));
                    }
                    not_hris_profile_training_course:

                    if (0 === strpos($pathinfo, '/profile/trainings/course/watch')) {
                        // hris_profile_training_watch
                        if (preg_match('#^/profile/trainings/course/watch/(?P<id>[^/]++)/(?P<chapter>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_profile_training_watch;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_training_watch')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\TrainingController::watchAction',));
                        }
                        not_hris_profile_training_watch:

                        // hris_profile_training_watch_first
                        if (preg_match('#^/profile/trainings/course/watch/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_profile_training_watch_first;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_training_watch_first')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\TrainingController::watchAction',));
                        }
                        not_hris_profile_training_watch_first:

                    }

                }

            }

            if (0 === strpos($pathinfo, '/profile/loan/request')) {
                // hris_profile_loan_request_index
                if ($pathinfo === '/profile/loan/requests') {
                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LoanRequestController::indexAction',  '_route' => 'hris_profile_loan_request_index',);
                }

                // hris_profile_loan_request_add_form
                if ($pathinfo === '/profile/loan/request') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_loan_request_add_form;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LoanRequestController::addFormAction',  '_route' => 'hris_profile_loan_request_add_form',);
                }
                not_hris_profile_loan_request_add_form:

                // hris_profile_loan_request_add_submit
                if ($pathinfo === '/profile/loan/request') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_profile_loan_request_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LoanRequestController::addSubmitAction',  '_route' => 'hris_profile_loan_request_add_submit',);
                }
                not_hris_profile_loan_request_add_submit:

                // hris_profile_loan_request_edit_form
                if (preg_match('#^/profile/loan/request/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_loan_request_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_loan_request_edit_form')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LoanRequestController::editFormAction',));
                }
                not_hris_profile_loan_request_edit_form:

                // hris_profile_loan_request_view_form
                if (0 === strpos($pathinfo, '/profile/loan/request/schedule') && preg_match('#^/profile/loan/request/schedule/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_loan_request_view_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_loan_request_view_form')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LoanRequestController::viewFormAction',));
                }
                not_hris_profile_loan_request_view_form:

                // hris_profile_loan_request_edit_submit
                if (preg_match('#^/profile/loan/request/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_profile_loan_request_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_loan_request_edit_submit')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LoanRequestController::editSubmitAction',));
                }
                not_hris_profile_loan_request_edit_submit:

                // hris_profile_loan_request_delete
                if (preg_match('#^/profile/loan/request/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_loan_request_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_loan_request_delete')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LoanRequestController::deleteAction',));
                }
                not_hris_profile_loan_request_delete:

                // hris_profile_loan_request_grid
                if ($pathinfo === '/profile/loan/requests/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_loan_request_grid;
                    }

                    return array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LoanRequestController::gridAction',  '_route' => 'hris_profile_loan_request_grid',);
                }
                not_hris_profile_loan_request_grid:

            }

            if (0 === strpos($pathinfo, '/profile/ajax')) {
                // hris_profile_loan_request_ajax_get
                if (0 === strpos($pathinfo, '/profile/ajax/loan/request') && preg_match('#^/profile/ajax/loan/request/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_loan_request_ajax_get;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_loan_request_ajax_get')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LoanRequestController::ajaxGetAction',));
                }
                not_hris_profile_loan_request_ajax_get:

                // hris_profile_loans_emp_ajax
                if (0 === strpos($pathinfo, '/profile/ajax/emp/loans') && preg_match('#^/profile/ajax/emp/loans/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_profile_loans_emp_ajax;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_loans_emp_ajax')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\LoanRequestController::ajaxGetEmpLoanAction',));
                }
                not_hris_profile_loans_emp_ajax:

            }

            // hris_profile_file_agree
            if (0 === strpos($pathinfo, '/profile/profile/myProfile/agree') && preg_match('#^/profile/profile/myProfile/agree(?:/(?P<file>[^/]++))?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_profile_file_agree;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_profile_file_agree')), array (  '_controller' => 'Hris\\ProfileBundle\\Controller\\EmployeeController::fileAgreementAction',  'file' => NULL,));
            }
            not_hris_profile_file_agree:

        }

        if (0 === strpos($pathinfo, '/company')) {
            // hris_com_info_get_cities
            if (0 === strpos($pathinfo, '/company/cities') && preg_match('#^/company/cities(?:/(?P<parent_id>[^/]++))?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_com_info_get_cities;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_com_info_get_cities')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::getChildLocationAction',  'parent_id' => NULL,));
            }
            not_hris_com_info_get_cities:

            // hris_com_info_get_states
            if (0 === strpos($pathinfo, '/company/states') && preg_match('#^/company/states(?:/(?P<parent_id>[^/]++))?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_com_info_get_states;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_com_info_get_states')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::getChildLocationAction',  'parent_id' => NULL,));
            }
            not_hris_com_info_get_states:

            if (0 === strpos($pathinfo, '/company/cominfo')) {
                // hris_com_info_index
                if ($pathinfo === '/company/cominfo') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_com_info_index;
                    }

                    return array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\ComInfoController::indexAction',  '_route' => 'hris_com_info_index',);
                }
                not_hris_com_info_index:

                // hris_com_info_index_submit
                if ($pathinfo === '/company/cominfo') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_com_info_index_submit;
                    }

                    return array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\ComInfoController::indexSubmitAction',  '_route' => 'hris_com_info_index_submit',);
                }
                not_hris_com_info_index_submit:

            }

            // hris_com_directory_index
            if ($pathinfo === '/company/directory') {
                return array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\DirectoryController::indexAction',  '_route' => 'hris_com_directory_index',);
            }

            if (0 === strpos($pathinfo, '/company/orgchart')) {
                // hris_com_orgchart_index
                if ($pathinfo === '/company/orgchart/chart') {
                    return array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\OrgChartController::indexAction',  '_route' => 'hris_com_orgchart_index',);
                }

                // hris_com_orgchart_add_form
                if ($pathinfo === '/company/orgchart/add') {
                    return array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\OrgChartController::addFormAction',  '_route' => 'hris_com_orgchart_add_form',);
                }

                if (0 === strpos($pathinfo, '/company/orgchart/nodes')) {
                    // hris_com_orgchart_remove_node
                    if ($pathinfo === '/company/orgchart/nodes/del') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_com_orgchart_remove_node;
                        }

                        return array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\OrgChartController::removePIDAction',  '_route' => 'hris_com_orgchart_remove_node',);
                    }
                    not_hris_com_orgchart_remove_node:

                    // hris_com_orgchart_set_head
                    if ($pathinfo === '/company/orgchart/nodes/emp/sethead') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_com_orgchart_set_head;
                        }

                        return array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\OrgChartController::ajaxSetAsHeaderAction',  '_route' => 'hris_com_orgchart_set_head',);
                    }
                    not_hris_com_orgchart_set_head:

                }

                // hris_com_orgchart_filter_emp
                if (0 === strpos($pathinfo, '/company/orgchart/filter/emp') && preg_match('#^/company/orgchart/filter/emp(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_com_orgchart_filter_emp;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_com_orgchart_filter_emp')), array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\OrgChartController::ajaxFilterEmployeeAction',  'id' => NULL,));
                }
                not_hris_com_orgchart_filter_emp:

                // hris_com_orgchart_get_chart
                if (0 === strpos($pathinfo, '/company/orgchart/display/chart') && preg_match('#^/company/orgchart/display/chart(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_com_orgchart_get_chart;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_com_orgchart_get_chart')), array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\OrgChartController::ajaxOrgChartGetAction',  'id' => NULL,));
                }
                not_hris_com_orgchart_get_chart:

                // hris_com_ajax_set_node
                if ($pathinfo === '/company/orgchart/add/node') {
                    return array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\OrgChartController::ajaxSetNodeAction',  '_route' => 'hris_com_ajax_set_node',);
                }

                // hris_com_ajax_disp_emp
                if ($pathinfo === '/company/orgchart/emp/disp') {
                    return array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\OrgChartController::displayEmpAction',  '_route' => 'hris_com_ajax_disp_emp',);
                }

                // hris_com_ajax_node_emp
                if (0 === strpos($pathinfo, '/company/orgchart/node/emp') && preg_match('#^/company/orgchart/node/emp(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_com_ajax_node_emp')), array (  '_controller' => 'Hris\\CompanyOverviewBundle\\Controller\\OrgChartController::ajaxGetNodeChartAction',  'id' => NULL,));
                }

            }

        }

        if (0 === strpos($pathinfo, '/payroll')) {
            if (0 === strpos($pathinfo, '/payroll/pay_earn_ded')) {
                // hris_payroll_earn_ded_index
                if ($pathinfo === '/payroll/pay_earn_deds') {
                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::indexAction',  '_route' => 'hris_payroll_earn_ded_index',);
                }

                // hris_payroll_earn_ded_add_form
                if ($pathinfo === '/payroll/pay_earn_ded') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_earn_ded_add_form;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::addFormAction',  '_route' => 'hris_payroll_earn_ded_add_form',);
                }
                not_hris_payroll_earn_ded_add_form:

                // hris_payroll_earn_ded_add_submit
                if ($pathinfo === '/payroll/pay_earn_ded') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_earn_ded_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::addSubmitAction',  '_route' => 'hris_payroll_earn_ded_add_submit',);
                }
                not_hris_payroll_earn_ded_add_submit:

                // hris_payroll_earn_ded_edit_form
                if (preg_match('#^/payroll/pay_earn_ded/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_earn_ded_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earn_ded_edit_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::editFormAction',));
                }
                not_hris_payroll_earn_ded_edit_form:

                // hris_payroll_earn_ded_edit_submit
                if (preg_match('#^/payroll/pay_earn_ded/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_earn_ded_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earn_ded_edit_submit')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::editSubmitAction',));
                }
                not_hris_payroll_earn_ded_edit_submit:

                // hris_payroll_earn_ded_delete
                if (preg_match('#^/payroll/pay_earn_ded/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_earn_ded_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earn_ded_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::deleteAction',));
                }
                not_hris_payroll_earn_ded_delete:

            }

            // hris_payroll_earn_ded_ajax_detials
            if (0 === strpos($pathinfo, '/payroll/ajax/pay_earn_ded/details') && preg_match('#^/payroll/ajax/pay_earn_ded/details/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_payroll_earn_ded_ajax_detials;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earn_ded_ajax_detials')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::ajaxDetailsAction',));
            }
            not_hris_payroll_earn_ded_ajax_detials:

            // hris_payroll_earn_ded_grid
            if ($pathinfo === '/payroll/pay_earn_deds/grid') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_payroll_earn_ded_grid;
                }

                return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::gridAction',  '_route' => 'hris_payroll_earn_ded_grid',);
            }
            not_hris_payroll_earn_ded_grid:

            if (0 === strpos($pathinfo, '/payroll/ajax/pay_earn_ded')) {
                // hris_payroll_earn_ded_ajax_get
                if (preg_match('#^/payroll/ajax/pay_earn_ded/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_earn_ded_ajax_get;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earn_ded_ajax_get')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::ajaxGetAction',));
                }
                not_hris_payroll_earn_ded_ajax_get:

                // hris_payroll_earn_ded_ajax_get_form
                if (preg_match('#^/payroll/ajax/pay_earn_ded/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_earn_ded_ajax_get_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earn_ded_ajax_get_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::ajaxGetFormAction',));
                }
                not_hris_payroll_earn_ded_ajax_get_form:

                // hris_payroll_earn_ded_ajax_add
                if ($pathinfo === '/payroll/ajax/pay_earn_ded') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_earn_ded_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::ajaxAddAction',  '_route' => 'hris_payroll_earn_ded_ajax_add',);
                }
                not_hris_payroll_earn_ded_ajax_add:

                // hris_payroll_earn_ded_ajax_save
                if (preg_match('#^/payroll/ajax/pay_earn_ded/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_earn_ded_ajax_save;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earn_ded_ajax_save')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayEarningDeductionController::ajaxSaveAction',));
                }
                not_hris_payroll_earn_ded_ajax_save:

            }

            if (0 === strpos($pathinfo, '/payroll/payroll')) {
                if (0 === strpos($pathinfo, '/payroll/payrolls')) {
                    // hris_payroll_ajax_test
                    if (0 === strpos($pathinfo, '/payroll/payrolls/test') && preg_match('#^/payroll/payrolls/test(?:/(?P<id>[^/]++)(?:/(?P<dfrom>[^/]++)(?:/(?P<dto>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_ajax_test;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_ajax_test')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\ComputationController::ajaxTestAction',  'id' => NULL,  'dfrom' => NULL,  'dto' => NULL,));
                    }
                    not_hris_payroll_ajax_test:

                    // hris_payroll_computation_index
                    if ($pathinfo === '/payroll/payrolls') {
                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\ComputationController::indexAction',  '_route' => 'hris_payroll_computation_index',);
                    }

                }

                if (0 === strpos($pathinfo, '/payroll/payroll/add')) {
                    // hris_payroll_computation_add_form
                    if ($pathinfo === '/payroll/payroll/add') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_computation_add_form;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\ComputationController::addFormAction',  '_route' => 'hris_payroll_computation_add_form',);
                    }
                    not_hris_payroll_computation_add_form:

                    // hris_payroll_computation_add_submit
                    if ($pathinfo === '/payroll/payroll/add') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_computation_add_submit;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\ComputationController::addSubmitAction',  '_route' => 'hris_payroll_computation_add_submit',);
                    }
                    not_hris_payroll_computation_add_submit:

                }

                // hris_payroll_computation_grid
                if ($pathinfo === '/payroll/payroll/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_computation_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\ComputationController::gridAction',  '_route' => 'hris_payroll_computation_grid',);
                }
                not_hris_payroll_computation_grid:

                // hris_payroll_computation_update_data
                if (0 === strpos($pathinfo, '/payroll/payrolls/update') && preg_match('#^/payroll/payrolls/update(?:/(?P<id>[^/]++)(?:/(?P<date_to>[^/]++)(?:/(?P<date_from>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_computation_update_data;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_computation_update_data')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\ComputationController::updateDataAction',  'id' => NULL,  'date_to' => NULL,  'date_from' => NULL,));
                }
                not_hris_payroll_computation_update_data:

            }

            // hris_payroll_batch_ded_filter
            if (0 === strpos($pathinfo, '/payroll/ajax/batch_ded') && preg_match('#^/payroll/ajax/batch_ded(?:/(?P<branch_id>[^/]++)(?:/(?P<dept_id>[^/]++))?)?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_payroll_batch_ded_filter;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_batch_ded_filter')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\BatchDeductionController::employeeFilterAction',  'branch_id' => NULL,  'dept_id' => NULL,));
            }
            not_hris_payroll_batch_ded_filter:

            if (0 === strpos($pathinfo, '/payroll/batch_deduction')) {
                // hris_payroll_batch_ded_index
                if (rtrim($pathinfo, '/') === '/payroll/batch_deduction') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_batch_ded_index;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'hris_payroll_batch_ded_index');
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\BatchDeductionController::indexAction',  '_route' => 'hris_payroll_batch_ded_index',);
                }
                not_hris_payroll_batch_ded_index:

                // hris_payroll_batch_ded_submit
                if ($pathinfo === '/payroll/batch_deduction/') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_batch_ded_submit;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\BatchDeductionController::saveScheduleAction',  '_route' => 'hris_payroll_batch_ded_submit',);
                }
                not_hris_payroll_batch_ded_submit:

                // hris_payroll_batch_ded_employees
                if (0 === strpos($pathinfo, '/payroll/batch_deduction/employees') && preg_match('#^/payroll/batch_deduction/employees/(?P<branch_id>[^/]++)/(?P<week_start>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_batch_ded_employees;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_batch_ded_employees')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\BatchDeductionController::getEmployeesBranchAction',));
                }
                not_hris_payroll_batch_ded_employees:

                // hris_payroll_batch_ded_print
                if (0 === strpos($pathinfo, '/payroll/batch_deduction/print') && preg_match('#^/payroll/batch_deduction/print/(?P<branch_id>[^/]++)/(?P<week_start>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_batch_ded_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_batch_ded_print')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\BatchDeductionController::printAction',));
                }
                not_hris_payroll_batch_ded_print:

            }

            if (0 === strpos($pathinfo, '/payroll/ssscontribution')) {
                // hris_payroll_sss_index
                if ($pathinfo === '/payroll/ssscontributions') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_sss_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySSSRateController::indexAction',  '_route' => 'hris_payroll_sss_index',);
                }
                not_hris_payroll_sss_index:

                // hris_payroll_sss_add_form
                if ($pathinfo === '/payroll/ssscontribution') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_sss_add_form;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySSSRateController::addFormAction',  '_route' => 'hris_payroll_sss_add_form',);
                }
                not_hris_payroll_sss_add_form:

                // hris_payroll_sss_add_submit
                if ($pathinfo === '/payroll/ssscontribution') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_sss_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySSSRateController::addSubmitAction',  '_route' => 'hris_payroll_sss_add_submit',);
                }
                not_hris_payroll_sss_add_submit:

                // hris_payroll_sss_edit_form
                if (preg_match('#^/payroll/ssscontribution/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_sss_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_sss_edit_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySSSRateController::editFormAction',));
                }
                not_hris_payroll_sss_edit_form:

                // hris_payroll_sss_edit_submit
                if (preg_match('#^/payroll/ssscontribution/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_sss_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_sss_edit_submit')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySSSRateController::editSubmitAction',));
                }
                not_hris_payroll_sss_edit_submit:

                // hris_payroll_sss_grid
                if ($pathinfo === '/payroll/ssscontributions/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_sss_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySSSRateController::gridAction',  '_route' => 'hris_payroll_sss_grid',);
                }
                not_hris_payroll_sss_grid:

                // hris_payroll_sss_delete
                if (preg_match('#^/payroll/ssscontribution/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_sss_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_sss_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySSSRateController::deleteAction',));
                }
                not_hris_payroll_sss_delete:

            }

            if (0 === strpos($pathinfo, '/payroll/ajax/ssscontribution')) {
                // hris_payroll_sss_ajax_get_form
                if (preg_match('#^/payroll/ajax/ssscontribution/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_sss_ajax_get_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_sss_ajax_get_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySSSRateController::ajaxGetFormAction',));
                }
                not_hris_payroll_sss_ajax_get_form:

                // hris_payroll_sss_ajax_add
                if ($pathinfo === '/payroll/ajax/ssscontribution') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_sss_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySSSRateController::ajaxAddAction',  '_route' => 'hris_payroll_sss_ajax_add',);
                }
                not_hris_payroll_sss_ajax_add:

                // hris_payroll_sss_ajax_save
                if (preg_match('#^/payroll/ajax/ssscontribution/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_sss_ajax_save;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_sss_ajax_save')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySSSRateController::ajaxSaveAction',));
                }
                not_hris_payroll_sss_ajax_save:

            }

            if (0 === strpos($pathinfo, '/payroll/pagibigcontribution')) {
                // hris_payroll_pagibig_index
                if ($pathinfo === '/payroll/pagibigcontributions') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_pagibig_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPagibigRateController::indexAction',  '_route' => 'hris_payroll_pagibig_index',);
                }
                not_hris_payroll_pagibig_index:

                // hris_payroll_pagibig_add_form
                if ($pathinfo === '/payroll/pagibigcontribution') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_pagibig_add_form;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPagibigRateController::addFormAction',  '_route' => 'hris_payroll_pagibig_add_form',);
                }
                not_hris_payroll_pagibig_add_form:

                // hris_payroll_pagibig_add_submit
                if ($pathinfo === '/payroll/pagibigcontribution') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_pagibig_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPagibigRateController::addSubmitAction',  '_route' => 'hris_payroll_pagibig_add_submit',);
                }
                not_hris_payroll_pagibig_add_submit:

                // hris_payroll_pagibig_edit_form
                if (preg_match('#^/payroll/pagibigcontribution/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_pagibig_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_pagibig_edit_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPagibigRateController::editFormAction',));
                }
                not_hris_payroll_pagibig_edit_form:

                // hris_payroll_pagibig_edit_submit
                if (preg_match('#^/payroll/pagibigcontribution/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_pagibig_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_pagibig_edit_submit')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPagibigRateController::editSubmitAction',));
                }
                not_hris_payroll_pagibig_edit_submit:

                // hris_payroll_pagibig_grid
                if ($pathinfo === '/payroll/pagibigcontributions/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_pagibig_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPagibigRateController::gridAction',  '_route' => 'hris_payroll_pagibig_grid',);
                }
                not_hris_payroll_pagibig_grid:

                // hris_payroll_pagibig_delete
                if (preg_match('#^/payroll/pagibigcontribution/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_pagibig_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_pagibig_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPagibigRateController::deleteAction',));
                }
                not_hris_payroll_pagibig_delete:

            }

            if (0 === strpos($pathinfo, '/payroll/ajax/pagibigcontribution')) {
                // hris_payroll_pagibig_ajax_get_form
                if (preg_match('#^/payroll/ajax/pagibigcontribution/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_pagibig_ajax_get_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_pagibig_ajax_get_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPagibigRateController::ajaxGetFormAction',));
                }
                not_hris_payroll_pagibig_ajax_get_form:

                // hris_payroll_pagibig_ajax_add
                if ($pathinfo === '/payroll/ajax/pagibigcontribution') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_pagibig_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPagibigRateController::ajaxAddAction',  '_route' => 'hris_payroll_pagibig_ajax_add',);
                }
                not_hris_payroll_pagibig_ajax_add:

                // hris_payroll_pagibig_ajax_save
                if (preg_match('#^/payroll/ajax/pagibigcontribution/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_pagibig_ajax_save;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_pagibig_ajax_save')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPagibigRateController::ajaxSaveAction',));
                }
                not_hris_payroll_pagibig_ajax_save:

            }

            if (0 === strpos($pathinfo, '/payroll/philhealth')) {
                // hris_payroll_philhealth_index
                if ($pathinfo === '/payroll/philhealths') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_philhealth_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPhilHealthRateController::indexAction',  '_route' => 'hris_payroll_philhealth_index',);
                }
                not_hris_payroll_philhealth_index:

                // hris_payroll_philhealth_add_form
                if ($pathinfo === '/payroll/philhealth') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_philhealth_add_form;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPhilHealthRateController::addFormAction',  '_route' => 'hris_payroll_philhealth_add_form',);
                }
                not_hris_payroll_philhealth_add_form:

                // hris_payroll_philhealth_add_submit
                if ($pathinfo === '/payroll/philhealth') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_philhealth_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPhilHealthRateController::addSubmitAction',  '_route' => 'hris_payroll_philhealth_add_submit',);
                }
                not_hris_payroll_philhealth_add_submit:

                // hris_payroll_philhealth_edit_form
                if (preg_match('#^/payroll/philhealth/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_philhealth_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_philhealth_edit_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPhilHealthRateController::editFormAction',));
                }
                not_hris_payroll_philhealth_edit_form:

                // hris_payroll_philhealth_edit_submit
                if (preg_match('#^/payroll/philhealth/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_philhealth_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_philhealth_edit_submit')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPhilHealthRateController::editSubmitAction',));
                }
                not_hris_payroll_philhealth_edit_submit:

                // hris_payroll_philhealth_grid
                if ($pathinfo === '/payroll/philhealths/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_philhealth_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPhilHealthRateController::gridAction',  '_route' => 'hris_payroll_philhealth_grid',);
                }
                not_hris_payroll_philhealth_grid:

                // hris_payroll_philhealth_delete
                if (preg_match('#^/payroll/philhealth/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_philhealth_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_philhealth_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPhilHealthRateController::deleteAction',));
                }
                not_hris_payroll_philhealth_delete:

            }

            if (0 === strpos($pathinfo, '/payroll/ajax/philhealth')) {
                // hris_payroll_philhealth_ajax_get_form
                if (preg_match('#^/payroll/ajax/philhealth/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_philhealth_ajax_get_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_philhealth_ajax_get_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPhilHealthRateController::ajaxGetFormAction',));
                }
                not_hris_payroll_philhealth_ajax_get_form:

                // hris_payroll_philhealth_ajax_add
                if ($pathinfo === '/payroll/ajax/philhealth') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_philhealth_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPhilHealthRateController::ajaxAddAction',  '_route' => 'hris_payroll_philhealth_ajax_add',);
                }
                not_hris_payroll_philhealth_ajax_add:

                // hris_payroll_philhealth_ajax_save
                if (preg_match('#^/payroll/ajax/philhealth/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_philhealth_ajax_save;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_philhealth_ajax_save')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayPhilHealthRateController::ajaxSaveAction',));
                }
                not_hris_payroll_philhealth_ajax_save:

            }

            if (0 === strpos($pathinfo, '/payroll/schedule')) {
                // hris_payroll_schedule_index
                if ($pathinfo === '/payroll/schedules') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_schedule_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayScheduleController::indexAction',  '_route' => 'hris_payroll_schedule_index',);
                }
                not_hris_payroll_schedule_index:

                // hris_payroll_schedule_add_form
                if ($pathinfo === '/payroll/schedule') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_schedule_add_form;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayScheduleController::addFormAction',  '_route' => 'hris_payroll_schedule_add_form',);
                }
                not_hris_payroll_schedule_add_form:

                // hris_payroll_schedule_add_submit
                if ($pathinfo === '/payroll/schedule') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_schedule_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayScheduleController::addSubmitAction',  '_route' => 'hris_payroll_schedule_add_submit',);
                }
                not_hris_payroll_schedule_add_submit:

                // hris_payroll_schedule_edit_form
                if (preg_match('#^/payroll/schedule/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_schedule_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_schedule_edit_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayScheduleController::editFormAction',));
                }
                not_hris_payroll_schedule_edit_form:

                // hris_payroll_schedule_edit_submit
                if (preg_match('#^/payroll/schedule/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_schedule_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_schedule_edit_submit')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayScheduleController::editSubmitAction',));
                }
                not_hris_payroll_schedule_edit_submit:

                // hris_payroll_schedule_delete
                if (preg_match('#^/payroll/schedule/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_schedule_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_schedule_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayScheduleController::deleteAction',));
                }
                not_hris_payroll_schedule_delete:

                // hris_payroll_schedule_grid
                if ($pathinfo === '/payroll/schedules/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_schedule_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayScheduleController::gridAction',  '_route' => 'hris_payroll_schedule_grid',);
                }
                not_hris_payroll_schedule_grid:

            }

            if (0 === strpos($pathinfo, '/payroll/tax')) {
                // hris_payroll_tax_index
                if ($pathinfo === '/payroll/taxes') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_tax_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayTaxMatrixController::indexAction',  '_route' => 'hris_payroll_tax_index',);
                }
                not_hris_payroll_tax_index:

                // hris_payroll_tax_add_form
                if ($pathinfo === '/payroll/tax') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_tax_add_form;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayTaxMatrixController::addFormAction',  '_route' => 'hris_payroll_tax_add_form',);
                }
                not_hris_payroll_tax_add_form:

                // hris_payroll_tax_add_submit
                if ($pathinfo === '/payroll/tax') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_tax_add_submit;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayTaxMatrixController::addSubmitAction',  '_route' => 'hris_payroll_tax_add_submit',);
                }
                not_hris_payroll_tax_add_submit:

                // hris_payroll_tax_edit_form
                if (preg_match('#^/payroll/tax/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_tax_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_tax_edit_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayTaxMatrixController::editFormAction',));
                }
                not_hris_payroll_tax_edit_form:

                // hris_payroll_tax_edit_submit
                if (preg_match('#^/payroll/tax/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_tax_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_tax_edit_submit')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayTaxMatrixController::editSubmitAction',));
                }
                not_hris_payroll_tax_edit_submit:

                // hris_payroll_tax_delete
                if (preg_match('#^/payroll/tax/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_tax_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_tax_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayTaxMatrixController::deleteAction',));
                }
                not_hris_payroll_tax_delete:

                // hris_payroll_tax_grid
                if ($pathinfo === '/payroll/taxes/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_tax_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayTaxMatrixController::gridAction',  '_route' => 'hris_payroll_tax_grid',);
                }
                not_hris_payroll_tax_grid:

            }

            if (0 === strpos($pathinfo, '/payroll/ajax/tax')) {
                // hris_payroll_tax_ajax_get_form
                if (preg_match('#^/payroll/ajax/tax/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_tax_ajax_get_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_tax_ajax_get_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayTaxMatrixController::ajaxGetFormAction',));
                }
                not_hris_payroll_tax_ajax_get_form:

                // hris_payroll_tax_ajax_add
                if ($pathinfo === '/payroll/ajax/tax') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_tax_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayTaxMatrixController::ajaxAddAction',  '_route' => 'hris_payroll_tax_ajax_add',);
                }
                not_hris_payroll_tax_ajax_add:

                // hris_payroll_tax_ajax_save
                if (preg_match('#^/payroll/ajax/tax/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_tax_ajax_save;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_tax_ajax_save')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayTaxMatrixController::ajaxSaveAction',));
                }
                not_hris_payroll_tax_ajax_save:

            }

            // hris_payroll_tax_lock
            if (0 === strpos($pathinfo, '/payroll/tax_lock') && preg_match('#^/payroll/tax_lock/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_payroll_tax_lock;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_tax_lock')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayTaxMatrixController::lockAction',));
            }
            not_hris_payroll_tax_lock:

            if (0 === strpos($pathinfo, '/payroll/settings')) {
                if (0 === strpos($pathinfo, '/payroll/settings/weekly')) {
                    // hris_payroll_weekly_index
                    if ($pathinfo === '/payroll/settings/weekly') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_weekly_index;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySettingsController::weeklyIndexAction',  '_route' => 'hris_payroll_weekly_index',);
                    }
                    not_hris_payroll_weekly_index:

                    // hris_payroll_weekly_submit
                    if ($pathinfo === '/payroll/settings/weekly') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_weekly_submit;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySettingsController::weeklySubmitAction',  '_route' => 'hris_payroll_weekly_submit',);
                    }
                    not_hris_payroll_weekly_submit:

                }

                if (0 === strpos($pathinfo, '/payroll/settings/semimonthly')) {
                    // hris_payroll_semimonthly_index
                    if ($pathinfo === '/payroll/settings/semimonthly') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_semimonthly_index;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySettingsController::semimonthlyIndexAction',  '_route' => 'hris_payroll_semimonthly_index',);
                    }
                    not_hris_payroll_semimonthly_index:

                    // hris_payroll_semimonthly_submit
                    if ($pathinfo === '/payroll/settings/semimonthly') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_semimonthly_submit;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySettingsController::semimonthlySubmitAction',  '_route' => 'hris_payroll_semimonthly_submit',);
                    }
                    not_hris_payroll_semimonthly_submit:

                }

                if (0 === strpos($pathinfo, '/payroll/settings/minimum')) {
                    // hris_payroll_setting_min_index
                    if ($pathinfo === '/payroll/settings/minimum') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_setting_min_index;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySettingsController::minIndexAction',  '_route' => 'hris_payroll_setting_min_index',);
                    }
                    not_hris_payroll_setting_min_index:

                    // hris_payroll_setting_min_submit
                    if ($pathinfo === '/payroll/settings/minimum') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_setting_min_submit;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySettingsController::minSubmitAction',  '_route' => 'hris_payroll_setting_min_submit',);
                    }
                    not_hris_payroll_setting_min_submit:

                }

                if (0 === strpos($pathinfo, '/payroll/settings/year')) {
                    // hris_payroll_setting_year_index
                    if ($pathinfo === '/payroll/settings/year') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_setting_year_index;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySettingsController::yearIndexAction',  '_route' => 'hris_payroll_setting_year_index',);
                    }
                    not_hris_payroll_setting_year_index:

                    // hris_payroll_setting_year_submit
                    if ($pathinfo === '/payroll/settings/year') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_setting_year_submit;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySettingsController::yearSubmitAction',  '_route' => 'hris_payroll_setting_year_submit',);
                    }
                    not_hris_payroll_setting_year_submit:

                }

                if (0 === strpos($pathinfo, '/payroll/settings/workingdays')) {
                    // hris_payroll_workingdays_index
                    if ($pathinfo === '/payroll/settings/workingdays') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_workingdays_index;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySettingsController::workingdaysIndexAction',  '_route' => 'hris_payroll_workingdays_index',);
                    }
                    not_hris_payroll_workingdays_index:

                    // hris_payroll_workingdays_submit
                    if ($pathinfo === '/payroll/settings/workingdays') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_workingdays_submit;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySettingsController::workingdaysSubmitAction',  '_route' => 'hris_payroll_workingdays_submit',);
                    }
                    not_hris_payroll_workingdays_submit:

                }

            }

            if (0 === strpos($pathinfo, '/payroll/generate')) {
                // hris_payroll_generate_index
                if ($pathinfo === '/payroll/generate') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_generate_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateController::indexAction',  '_route' => 'hris_payroll_generate_index',);
                }
                not_hris_payroll_generate_index:

                // hris_payroll_generate_submit
                if ($pathinfo === '/payroll/generate') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_generate_submit;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateController::filterAction',  '_route' => 'hris_payroll_generate_submit',);
                }
                not_hris_payroll_generate_submit:

            }

            // hris_payroll_earning_add_ajax
            if (0 === strpos($pathinfo, '/payroll/add_earning') && preg_match('#^/payroll/add_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_payroll_earning_add_ajax;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earning_add_ajax')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateController::addEarningAction',));
            }
            not_hris_payroll_earning_add_ajax:

            // hris_payroll_earning_delete
            if (0 === strpos($pathinfo, '/payroll/delete_earning') && preg_match('#^/payroll/delete_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_payroll_earning_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_earning_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateController::deleteEarningAction',));
            }
            not_hris_payroll_earning_delete:

            // hris_payroll_deduction_add_ajax
            if (0 === strpos($pathinfo, '/payroll/add_deduction') && preg_match('#^/payroll/add_deduction/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_payroll_deduction_add_ajax;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_deduction_add_ajax')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateController::addDeductionAction',));
            }
            not_hris_payroll_deduction_add_ajax:

            // hris_payroll_deduction_delete
            if (0 === strpos($pathinfo, '/payroll/delete_deduction') && preg_match('#^/payroll/delete_deduction/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_payroll_deduction_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_deduction_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateController::deleteDeductionAction',));
            }
            not_hris_payroll_deduction_delete:

            // hris_payroll_lock_all
            if ($pathinfo === '/payroll/lock/all') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_payroll_lock_all;
                }

                return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateController::lockAllAction',  '_route' => 'hris_payroll_lock_all',);
            }
            not_hris_payroll_lock_all:

            // hris_payroll_details_print
            if (0 === strpos($pathinfo, '/payroll/details/review') && preg_match('#^/payroll/details/review/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_payroll_details_print;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_details_print')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateController::printAction',));
            }
            not_hris_payroll_details_print:

            // hris_payroll_progress
            if ($pathinfo === '/payroll/progress') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_payroll_progress;
                }

                return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateController::getProgressAction',  '_route' => 'hris_payroll_progress',);
            }
            not_hris_payroll_progress:

            if (0 === strpos($pathinfo, '/payroll/view')) {
                // hris_payroll_view_index
                if ($pathinfo === '/payroll/view') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_view_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayrollController::indexAction',  '_route' => 'hris_payroll_view_index',);
                }
                not_hris_payroll_view_index:

                // hris_payroll_view_grid
                if ($pathinfo === '/payroll/view/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_view_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayrollController::gridAction',  '_route' => 'hris_payroll_view_grid',);
                }
                not_hris_payroll_view_grid:

            }

            if (0 === strpos($pathinfo, '/payroll/review')) {
                // hris_payroll_review_index
                if ($pathinfo === '/payroll/review') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_review_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayrollReviewController::indexAction',  '_route' => 'hris_payroll_review_index',);
                }
                not_hris_payroll_review_index:

                // hris_payroll_review_lock
                if (0 === strpos($pathinfo, '/payroll/review/lock') && preg_match('#^/payroll/review/lock/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_review_lock;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_review_lock')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayrollReviewController::lockAllAction',));
                }
                not_hris_payroll_review_lock:

                // hris_payroll_review_grid
                if ($pathinfo === '/payroll/review/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_review_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayrollReviewController::gridAction',  '_route' => 'hris_payroll_review_grid',);
                }
                not_hris_payroll_review_grid:

            }

            if (0 === strpos($pathinfo, '/payroll/cutoff')) {
                // hris_payroll_cutoff_index
                if ($pathinfo === '/payroll/cutoff') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_cutoff_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayrollCutoffController::indexAction',  '_route' => 'hris_payroll_cutoff_index',);
                }
                not_hris_payroll_cutoff_index:

                // hris_payroll_cutoff_grid
                if ($pathinfo === '/payroll/cutoff/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_cutoff_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayrollCutoffController::gridAction',  '_route' => 'hris_payroll_cutoff_grid',);
                }
                not_hris_payroll_cutoff_grid:

                if (0 === strpos($pathinfo, '/payroll/cutoff/detail')) {
                    // hris_payroll_cutoff_detail_index
                    if (preg_match('#^/payroll/cutoff/detail(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_cutoff_detail_index;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_cutoff_detail_index')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayrollCutoffDetailController::indexAction',  'id' => NULL,));
                    }
                    not_hris_payroll_cutoff_detail_index:

                    // hris_payroll_cutoff_detail_grid
                    if (0 === strpos($pathinfo, '/payroll/cutoff/detail/grid') && preg_match('#^/payroll/cutoff/detail/grid(?:/(?P<id>[^/]++))?$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_cutoff_detail_grid;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_cutoff_detail_grid')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayrollCutoffDetailController::gridPayAction',  'id' => NULL,));
                    }
                    not_hris_payroll_cutoff_detail_grid:

                }

            }

            if (0 === strpos($pathinfo, '/payroll/13th')) {
                // hris_payroll_thirteenth_index
                if ($pathinfo === '/payroll/13th') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_thirteenth_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\ThirteenthController::indexAction',  '_route' => 'hris_payroll_thirteenth_index',);
                }
                not_hris_payroll_thirteenth_index:

                // hris_payroll_thirteenth_submit
                if ($pathinfo === '/payroll/13th') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_thirteenth_submit;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\ThirteenthController::filterAction',  '_route' => 'hris_payroll_thirteenth_submit',);
                }
                not_hris_payroll_thirteenth_submit:

                // hris_payroll_thirteenth_view_index
                if ($pathinfo === '/payroll/13th/list') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_thirteenth_view_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\Pay13thController::indexAction',  '_route' => 'hris_payroll_thirteenth_view_index',);
                }
                not_hris_payroll_thirteenth_view_index:

                // hris_payroll_thirteenth_view_grid
                if ($pathinfo === '/payroll/13th/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_thirteenth_view_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\Pay13thController::gridAction',  '_route' => 'hris_payroll_thirteenth_view_grid',);
                }
                not_hris_payroll_thirteenth_view_grid:

                if (0 === strpos($pathinfo, '/payroll/13th/details')) {
                    // hris_payroll_thirteenth_details_index
                    if (preg_match('#^/payroll/13th/details/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_thirteenth_details_index;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_thirteenth_details_index')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\ThirteenthController::detailsAction',));
                    }
                    not_hris_payroll_thirteenth_details_index:

                    // hris_payroll_thirteenth_details_print
                    if (preg_match('#^/payroll/13th/details/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_thirteenth_details_print;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_thirteenth_details_print')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\ThirteenthController::printAction',));
                    }
                    not_hris_payroll_thirteenth_details_print:

                }

                // hris_payroll_thirteenth_add_entry
                if (0 === strpos($pathinfo, '/payroll/13th/entry') && preg_match('#^/payroll/13th/entry/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_thirteenth_add_entry;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_thirteenth_add_entry')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\Pay13thController::addEntryAction',));
                }
                not_hris_payroll_thirteenth_add_entry:

                // hris_payroll_thirteenth_delete_entry
                if (0 === strpos($pathinfo, '/payroll/13th/delete-entry') && preg_match('#^/payroll/13th/delete\\-entry/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_thirteenth_delete_entry;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_thirteenth_delete_entry')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\Pay13thController::deleteEntryAction',));
                }
                not_hris_payroll_thirteenth_delete_entry:

                if (0 === strpos($pathinfo, '/payroll/13th/lock')) {
                    // hris_payroll_thirteenth_lock
                    if (preg_match('#^/payroll/13th/lock/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_thirteenth_lock;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_thirteenth_lock')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\Pay13thController::lockAction',));
                    }
                    not_hris_payroll_thirteenth_lock:

                    // hris_payroll_thirteenth_lock_all
                    if ($pathinfo === '/payroll/13th/lock/all/pay') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_thirteenth_lock_all;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\Pay13thController::lockAllAction',  '_route' => 'hris_payroll_thirteenth_lock_all',);
                    }
                    not_hris_payroll_thirteenth_lock_all:

                }

            }

            if (0 === strpos($pathinfo, '/payroll/weekly')) {
                if (0 === strpos($pathinfo, '/payroll/weekly/generate')) {
                    // hris_payroll_weekly_generate_index
                    if ($pathinfo === '/payroll/weekly/generate') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_weekly_generate_index;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateWeeklyController::indexAction',  '_route' => 'hris_payroll_weekly_generate_index',);
                    }
                    not_hris_payroll_weekly_generate_index:

                    // hris_payroll_weekly_generate_submit
                    if ($pathinfo === '/payroll/weekly/generate') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_weekly_generate_submit;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateWeeklyController::filterAction',  '_route' => 'hris_payroll_weekly_generate_submit',);
                    }
                    not_hris_payroll_weekly_generate_submit:

                }

                // hris_payroll_weekly_details_index
                if (0 === strpos($pathinfo, '/payroll/weekly/details') && preg_match('#^/payroll/weekly/details/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_weekly_details_index;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_weekly_details_index')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateWeeklyController::detailsAction',));
                }
                not_hris_payroll_weekly_details_index:

                // hris_payroll_weekly_earning_add_ajax
                if (0 === strpos($pathinfo, '/payroll/weekly/add_earning') && preg_match('#^/payroll/weekly/add_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_weekly_earning_add_ajax;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_weekly_earning_add_ajax')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateWeeklyController::addEarningAction',));
                }
                not_hris_payroll_weekly_earning_add_ajax:

                // hris_payroll_weekly_earning_delete
                if (0 === strpos($pathinfo, '/payroll/weekly/delete_earning') && preg_match('#^/payroll/weekly/delete_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_weekly_earning_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_weekly_earning_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateWeeklyController::deleteEarningAction',));
                }
                not_hris_payroll_weekly_earning_delete:

                // hris_payroll_weekly_deduction_add_ajax
                if (0 === strpos($pathinfo, '/payroll/weekly/add_deduction') && preg_match('#^/payroll/weekly/add_deduction/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_weekly_deduction_add_ajax;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_weekly_deduction_add_ajax')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateWeeklyController::addDeductionAction',));
                }
                not_hris_payroll_weekly_deduction_add_ajax:

                // hris_payroll_weekly_deduction_delete
                if (0 === strpos($pathinfo, '/payroll/weekly/delete_deduction') && preg_match('#^/payroll/weekly/delete_deduction/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_weekly_deduction_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_weekly_deduction_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateWeeklyController::deleteDeductionAction',));
                }
                not_hris_payroll_weekly_deduction_delete:

                // hris_payroll_weekly_lock
                if (0 === strpos($pathinfo, '/payroll/weekly/lock') && preg_match('#^/payroll/weekly/lock/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_weekly_lock;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_weekly_lock')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateWeeklyController::lockAction',));
                }
                not_hris_payroll_weekly_lock:

                // hris_payroll_weekly_details_print
                if (0 === strpos($pathinfo, '/payroll/weekly/details') && preg_match('#^/payroll/weekly/details/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_weekly_details_print;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_weekly_details_print')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateWeeklyController::printAction',));
                }
                not_hris_payroll_weekly_details_print:

            }

            if (0 === strpos($pathinfo, '/payroll/13th/settings')) {
                // hris_payroll_thirteenth_setting_index
                if ($pathinfo === '/payroll/13th/settings') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_thirteenth_setting_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayThirteenthSettingsController::IndexAction',  '_route' => 'hris_payroll_thirteenth_setting_index',);
                }
                not_hris_payroll_thirteenth_setting_index:

                if (0 === strpos($pathinfo, '/payroll/13th/settings/add')) {
                    // hris_payroll_thirteenth_setting_add_form
                    if ($pathinfo === '/payroll/13th/settings/add') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_thirteenth_setting_add_form;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayThirteenthSettingsController::addFormAction',  '_route' => 'hris_payroll_thirteenth_setting_add_form',);
                    }
                    not_hris_payroll_thirteenth_setting_add_form:

                    // hris_payroll_thirteenth_setting_add_submit
                    if ($pathinfo === '/payroll/13th/settings/add') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_thirteenth_setting_add_submit;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayThirteenthSettingsController::addSubmitAction',  '_route' => 'hris_payroll_thirteenth_setting_add_submit',);
                    }
                    not_hris_payroll_thirteenth_setting_add_submit:

                }

                // hris_payroll_thirteenth_setting_grid
                if ($pathinfo === '/payroll/13th/settings/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_thirteenth_setting_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayThirteenthSettingsController::gridAction',  '_route' => 'hris_payroll_thirteenth_setting_grid',);
                }
                not_hris_payroll_thirteenth_setting_grid:

                // hris_payroll_thirteenth_setting_add_ajax
                if (preg_match('#^/payroll/13th/settings/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_thirteenth_setting_add_ajax;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_thirteenth_setting_add_ajax')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayThirteenthSettingsController::addAction',));
                }
                not_hris_payroll_thirteenth_setting_add_ajax:

                // hris_payroll_thirteenth_setting_ajax_add
                if ($pathinfo === '/payroll/13th/settings/ajax/add') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_thirteenth_setting_ajax_add;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayThirteenthSettingsController::ajaxAddAction',  '_route' => 'hris_payroll_thirteenth_setting_ajax_add',);
                }
                not_hris_payroll_thirteenth_setting_ajax_add:

                // hris_payroll_thirteenth_setting_delete
                if (preg_match('#^/payroll/13th/settings/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_thirteenth_setting_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_thirteenth_setting_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayThirteenthSettingsController::deleteAction',));
                }
                not_hris_payroll_thirteenth_setting_delete:

                // hris_payroll_thirteenth_setting_edit_form
                if (preg_match('#^/payroll/13th/settings/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_thirteenth_setting_edit_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_thirteenth_setting_edit_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayThirteenthSettingsController::editFormAction',));
                }
                not_hris_payroll_thirteenth_setting_edit_form:

                // hris_payroll_thirteenth_setting_edit_submit
                if (preg_match('#^/payroll/13th/settings/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_thirteenth_setting_edit_submit;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_thirteenth_setting_edit_submit')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayThirteenthSettingsController::editSubmitAction',));
                }
                not_hris_payroll_thirteenth_setting_edit_submit:

            }

            if (0 === strpos($pathinfo, '/payroll/ajax/13th/settings')) {
                // hris_payroll_thirteenth_setting_ajax_save
                if (preg_match('#^/payroll/ajax/13th/settings/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_thirteenth_setting_ajax_save;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_thirteenth_setting_ajax_save')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayThirteenthSettingsController::ajaxSaveAction',));
                }
                not_hris_payroll_thirteenth_setting_ajax_save:

                // hris_payroll_thirteenth_setting_ajax_get_form
                if (preg_match('#^/payroll/ajax/13th/settings/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_thirteenth_setting_ajax_get_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_thirteenth_setting_ajax_get_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PayThirteenthSettingsController::ajaxGetFormAction',));
                }
                not_hris_payroll_thirteenth_setting_ajax_get_form:

            }

            if (0 === strpos($pathinfo, '/payroll/separation')) {
                // hris_payroll_employee_separation_submit
                if ($pathinfo === '/payroll/separation') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_employee_separation_submit;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::filterAction',  '_route' => 'hris_payroll_employee_separation_submit',);
                }
                not_hris_payroll_employee_separation_submit:

                // hris_payroll_employee_separation_view_index
                if ($pathinfo === '/payroll/separation/list') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_employee_separation_view_index;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::indexAction',  '_route' => 'hris_payroll_employee_separation_view_index',);
                }
                not_hris_payroll_employee_separation_view_index:

                // hris_payroll_employee_separation_view_grid
                if ($pathinfo === '/payroll/separation/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_employee_separation_view_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::gridAction',  '_route' => 'hris_payroll_employee_separation_view_grid',);
                }
                not_hris_payroll_employee_separation_view_grid:

                if (0 === strpos($pathinfo, '/payroll/separation/details')) {
                    // hris_payroll_employee_separation_details_index
                    if (preg_match('#^/payroll/separation/details/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_employee_separation_details_index;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_employee_separation_details_index')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::detailsAction',));
                    }
                    not_hris_payroll_employee_separation_details_index:

                    // hris_payroll_employee_separation_details_print
                    if (preg_match('#^/payroll/separation/details/(?P<id>[^/]++)/print$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_employee_separation_details_print;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_employee_separation_details_print')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::printAction',));
                    }
                    not_hris_payroll_employee_separation_details_print:

                }

                // hris_payroll_employee_separation_add_entry
                if (0 === strpos($pathinfo, '/payroll/separation/entry') && preg_match('#^/payroll/separation/entry/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_employee_separation_add_entry;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_employee_separation_add_entry')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::addEntryAction',));
                }
                not_hris_payroll_employee_separation_add_entry:

                // hris_payroll_employee_separation_delete_entry
                if (0 === strpos($pathinfo, '/payroll/separation/delete-entry') && preg_match('#^/payroll/separation/delete\\-entry/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_employee_separation_delete_entry;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_employee_separation_delete_entry')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::deleteEntryAction',));
                }
                not_hris_payroll_employee_separation_delete_entry:

                // hris_payroll_separation_earning_add_ajax
                if (0 === strpos($pathinfo, '/payroll/separation/add_earning') && preg_match('#^/payroll/separation/add_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_separation_earning_add_ajax;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_separation_earning_add_ajax')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::addEarningAction',));
                }
                not_hris_payroll_separation_earning_add_ajax:

                // hris_payroll_separation_earning_delete
                if (0 === strpos($pathinfo, '/payroll/separation/delete_earning') && preg_match('#^/payroll/separation/delete_earning/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_separation_earning_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_separation_earning_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::deleteEarningAction',));
                }
                not_hris_payroll_separation_earning_delete:

                // hris_payroll_separation_deduction_add_ajax
                if (0 === strpos($pathinfo, '/payroll/separation/add_deduction') && preg_match('#^/payroll/separation/add_deduction/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_separation_deduction_add_ajax;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_separation_deduction_add_ajax')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::addDeductionAction',));
                }
                not_hris_payroll_separation_deduction_add_ajax:

                // hris_payroll_separation_deduction_delete
                if (0 === strpos($pathinfo, '/payroll/separation/delete_deduction') && preg_match('#^/payroll/separation/delete_deduction/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_separation_deduction_delete;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_separation_deduction_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::deleteDeductionAction',));
                }
                not_hris_payroll_separation_deduction_delete:

                // hris_payroll_employee_separation_lock
                if (0 === strpos($pathinfo, '/payroll/separation/lock') && preg_match('#^/payroll/separation/lock/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_employee_separation_lock;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_employee_separation_lock')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::lockAction',));
                }
                not_hris_payroll_employee_separation_lock:

                // hris_payroll_employee_separation_grid
                if ($pathinfo === '/payroll/separation/grid') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_employee_separation_grid;
                    }

                    return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::gridAction',  '_route' => 'hris_payroll_employee_separation_grid',);
                }
                not_hris_payroll_employee_separation_grid:

                if (0 === strpos($pathinfo, '/payroll/separation/settings')) {
                    // hris_payroll_separation_setting_index
                    if ($pathinfo === '/payroll/separation/settings') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_separation_setting_index;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::indexAction',  '_route' => 'hris_payroll_separation_setting_index',);
                    }
                    not_hris_payroll_separation_setting_index:

                    if (0 === strpos($pathinfo, '/payroll/separation/settings/add')) {
                        // hris_payroll_separation_setting_add_form
                        if ($pathinfo === '/payroll/separation/settings/add') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_payroll_separation_setting_add_form;
                            }

                            return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::addFormAction',  '_route' => 'hris_payroll_separation_setting_add_form',);
                        }
                        not_hris_payroll_separation_setting_add_form:

                        // hris_payroll_separation_setting_add_submit
                        if ($pathinfo === '/payroll/separation/settings/add') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_payroll_separation_setting_add_submit;
                            }

                            return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::addSubmitAction',  '_route' => 'hris_payroll_separation_setting_add_submit',);
                        }
                        not_hris_payroll_separation_setting_add_submit:

                    }

                    // hris_payroll_separation_setting_grid
                    if ($pathinfo === '/payroll/separation/settings/grid') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_separation_setting_grid;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::gridAction',  '_route' => 'hris_payroll_separation_setting_grid',);
                    }
                    not_hris_payroll_separation_setting_grid:

                    // hris_payroll_separation_setting_add_ajax
                    if (preg_match('#^/payroll/separation/settings/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_separation_setting_add_ajax;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_separation_setting_add_ajax')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::addAction',));
                    }
                    not_hris_payroll_separation_setting_add_ajax:

                    // hris_payroll_separation_setting_ajax_add
                    if ($pathinfo === '/payroll/separation/settings/ajax/add') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_separation_setting_ajax_add;
                        }

                        return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::ajaxAddAction',  '_route' => 'hris_payroll_separation_setting_ajax_add',);
                    }
                    not_hris_payroll_separation_setting_ajax_add:

                    // hris_payroll_separation_setting_delete
                    if (preg_match('#^/payroll/separation/settings/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_separation_setting_delete;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_separation_setting_delete')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::deleteAction',));
                    }
                    not_hris_payroll_separation_setting_delete:

                    // hris_payroll_separation_setting_edit_form
                    if (preg_match('#^/payroll/separation/settings/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_payroll_separation_setting_edit_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_separation_setting_edit_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::editFormAction',));
                    }
                    not_hris_payroll_separation_setting_edit_form:

                    // hris_payroll_separation_setting_edit_submit
                    if (preg_match('#^/payroll/separation/settings/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_payroll_separation_setting_edit_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_separation_setting_edit_submit')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::editSubmitAction',));
                    }
                    not_hris_payroll_separation_setting_edit_submit:

                }

            }

            if (0 === strpos($pathinfo, '/payroll/ajax/separation/settings')) {
                // hris_payroll_separation_setting_ajax_save
                if (preg_match('#^/payroll/ajax/separation/settings/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_hris_payroll_separation_setting_ajax_save;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_separation_setting_ajax_save')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::ajaxSaveAction',));
                }
                not_hris_payroll_separation_setting_ajax_save:

                // hris_payroll_separation_setting_ajax_get_form
                if (preg_match('#^/payroll/ajax/separation/settings/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_payroll_separation_setting_ajax_get_form;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_separation_setting_ajax_get_form')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationSettingsController::ajaxGetFormAction',));
                }
                not_hris_payroll_separation_setting_ajax_get_form:

            }

            // hris_payroll_details_index
            if (0 === strpos($pathinfo, '/payroll/details') && preg_match('#^/payroll/details/(?P<id>[^/]++)/(?P<link_type>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_payroll_details_index;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_details_index')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateController::detailsAction',));
            }
            not_hris_payroll_details_index:

            // hris_payroll_lock
            if (0 === strpos($pathinfo, '/payroll/lock') && preg_match('#^/payroll/lock/(?P<id>[^/]++)/(?P<link_type>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_payroll_lock;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_payroll_lock')), array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\GenerateController::lockAction',));
            }
            not_hris_payroll_lock:

        }

        // hris_dashboard_index
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'hris_dashboard_index');
            }

            return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\DashboardController::indexAction',  '_route' => 'hris_dashboard_index',);
        }

        if (0 === strpos($pathinfo, '/jobvacancy')) {
            // hris_dashboard_job_vacancy
            if ($pathinfo === '/jobvacancy') {
                return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\JobVacancyController::indexAction',  '_route' => 'hris_dashboard_job_vacancy',);
            }

            // hris_dashboard_job_vacancy_grid
            if ($pathinfo === '/jobvacancy/grid') {
                return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\JobVacancyController::gridVacancyAction',  '_route' => 'hris_dashboard_job_vacancy_grid',);
            }

        }

        // hris_dashboard_shortlisted_applicant
        if ($pathinfo === '/shortlistedapplicant') {
            return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ShortlistedApplicantController::indexAction',  '_route' => 'hris_dashboard_shortlisted_applicant',);
        }

        // hris_dashboard_pending_request
        if ($pathinfo === '/pendingrequest') {
            return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\PendingRequestController::indexAction',  '_route' => 'hris_dashboard_pending_request',);
        }

        // hris_dashboard_reimburse_request
        if ($pathinfo === '/reimburserequest') {
            return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\PendingRequestController::reimburseIndexAction',  '_route' => 'hris_dashboard_reimburse_request',);
        }

        // hris_dashboard_pending_grid_request
        if (0 === strpos($pathinfo, '/pendingrequest') && preg_match('#^/pendingrequest(?:/(?P<request>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_dashboard_pending_grid_request')), array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\PendingRequestController::gridRequestAction',  'request' => NULL,  'date_from' => NULL,  'date_to' => NULL,));
        }

        // hris_dashboard_schedules
        if ($pathinfo === '/appschedules') {
            return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\SchedulesController::indexAction',  '_route' => 'hris_dashboard_schedules',);
        }

        // hris_dashboard_scheduled_interview
        if ($pathinfo === '/scheduledinterview') {
            return array (  '_controller' => 'HrisDashboardBundle:ScheduledInterview:index',  '_route' => 'hris_dashboard_scheduled_interview',);
        }

        // hris_dashboard_calendar
        if ($pathinfo === '/calendar') {
            return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\CalendarController::indexAction',  '_route' => 'hris_dashboard_calendar',);
        }

        // hris_dashboard_training_monitoring
        if ($pathinfo === '/training') {
            return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\TrainingMonitoringController::indexAction',  '_route' => 'hris_dashboard_training_monitoring',);
        }

        if (0 === strpos($pathinfo, '/priority')) {
            // hris_dashboard_priority
            if ($pathinfo === '/priority') {
                return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\PriorityController::indexAction',  '_route' => 'hris_dashboard_priority',);
            }

            // hris_dashboard_priority_grid
            if ($pathinfo === '/priority/grid') {
                return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\PriorityController::gridAction',  '_route' => 'hris_dashboard_priority_grid',);
            }

        }

        if (0 === strpos($pathinfo, '/reminder')) {
            if (0 === strpos($pathinfo, '/reminders')) {
                // hris_dashboard_reminder_index
                if ($pathinfo === '/reminders') {
                    return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ReminderController::indexAction',  '_route' => 'hris_dashboard_reminder_index',);
                }

                // hris_dashboard_reminder_grid
                if ($pathinfo === '/reminders/grid') {
                    return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ReminderController::gridAction',  '_route' => 'hris_dashboard_reminder_grid',);
                }

            }

            // hris_dashboard_reminder_add_form
            if ($pathinfo === '/reminder') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_dashboard_reminder_add_form;
                }

                return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ReminderController::addFormAction',  '_route' => 'hris_dashboard_reminder_add_form',);
            }
            not_hris_dashboard_reminder_add_form:

        }

        if (0 === strpos($pathinfo, '/ajax/reminder')) {
            // hris_dashboard_reminder_ajax_add
            if ($pathinfo === '/ajax/reminders') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_dashboard_reminder_ajax_add;
                }

                return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ReminderController::ajaxAddAction',  '_route' => 'hris_dashboard_reminder_ajax_add',);
            }
            not_hris_dashboard_reminder_ajax_add:

            // hris_dashboard_reminder_ajax_save
            if (preg_match('#^/ajax/reminder/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_dashboard_reminder_ajax_save;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_dashboard_reminder_ajax_save')), array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ReminderController::ajaxSaveAction',));
            }
            not_hris_dashboard_reminder_ajax_save:

            // hris_dashboard_reminder_ajax_get
            if (preg_match('#^/ajax/reminder/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_dashboard_reminder_ajax_get;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_dashboard_reminder_ajax_get')), array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ReminderController::ajaxGetAction',));
            }
            not_hris_dashboard_reminder_ajax_get:

            // hris_dashboard_reminder_ajax_get_form
            if (preg_match('#^/ajax/reminder/(?P<id>[^/]++)/form$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_dashboard_reminder_ajax_get_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_dashboard_reminder_ajax_get_form')), array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ReminderController::ajaxGetFormAction',));
            }
            not_hris_dashboard_reminder_ajax_get_form:

        }

        if (0 === strpos($pathinfo, '/reminder')) {
            // hris_dashboard_reminder_add_submit
            if ($pathinfo === '/reminder') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_dashboard_reminder_add_submit;
                }

                return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ReminderController::addSubmitAction',  '_route' => 'hris_dashboard_reminder_add_submit',);
            }
            not_hris_dashboard_reminder_add_submit:

            // hris_dashboard_reminder_delete
            if (preg_match('#^/reminder/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_dashboard_reminder_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_dashboard_reminder_delete')), array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ReminderController::deleteAction',));
            }
            not_hris_dashboard_reminder_delete:

            // hris_dashboard_reminder_edit_form
            if (preg_match('#^/reminder/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_hris_dashboard_reminder_edit_form;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_dashboard_reminder_edit_form')), array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ReminderController::editFormAction',));
            }
            not_hris_dashboard_reminder_edit_form:

            // hris_dashboard_reminder_edit_submit
            if (preg_match('#^/reminder/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_hris_dashboard_reminder_edit_submit;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_dashboard_reminder_edit_submit')), array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ReminderController::editSubmitAction',));
            }
            not_hris_dashboard_reminder_edit_submit:

        }

        // hris_dashboard_training_monitoring_grid_request
        if (0 === strpos($pathinfo, '/training') && preg_match('#^/training(?:/(?P<request>[^/]++)(?:/(?P<date_from>[^/]++)(?:/(?P<date_to>[^/]++))?)?)?$#s', $pathinfo, $matches)) {
            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_dashboard_training_monitoring_grid_request')), array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\TrainingMonitoringController::gridRequestAction',  'request' => 'Going',  'date_from' => NULL,  'date_to' => NULL,));
        }

        // hris_attendance_emp-attendance_index
        if ($pathinfo === '/emp-attendances') {
            return array (  '_controller' => 'Hris\\AttendanceBundle\\Controller\\AttendanceController::indexAction',  '_route' => 'hris_attendance_emp-attendance_index',);
        }

        // hris_memo_index
        if ($pathinfo === '/memo') {
            return array (  '_controller' => 'Hris\\MemoBundle\\Controller\\MemoController::indexAction',  '_route' => 'hris_memo_index',);
        }

        // hris_payroll_employee_separation_index
        if ($pathinfo === '/separation') {
            return array (  '_controller' => 'Hris\\PayrollBundle\\Controller\\PaySeparationController::indexAction',  '_route' => 'hris_payroll_employee_separation_index',);
        }

        if (0 === strpos($pathinfo, '/pending')) {
            if (0 === strpos($pathinfo, '/pendingovertime')) {
                // hris_dashboard_pending_overtime
                if ($pathinfo === '/pendingovertime') {
                    return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\OvertimeRequestController::indexAction',  '_route' => 'hris_dashboard_pending_overtime',);
                }

                // hris_dashboard_pending_grid_overtime
                if ($pathinfo === '/pendingovertime/grid') {
                    return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\OvertimeRequestController::gridOvertimeAction',  '_route' => 'hris_dashboard_pending_grid_overtime',);
                }

            }

            if (0 === strpos($pathinfo, '/pendingleave')) {
                // hris_dashboard_pending_leave
                if ($pathinfo === '/pendingleave') {
                    return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\LeaveRequestController::indexAction',  '_route' => 'hris_dashboard_pending_leave',);
                }

                // hris_dashboard_pending_grid_leave
                if ($pathinfo === '/pendingleave/grid') {
                    return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\LeaveRequestController::gridLeaveAction',  '_route' => 'hris_dashboard_pending_grid_leave',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/contract_end')) {
            // hris_dashboard_contract_ending
            if ($pathinfo === '/contract_ending') {
                return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ContractEndController::endingAction',  '_route' => 'hris_dashboard_contract_ending',);
            }

            // hris_dashboard_contract_ended
            if ($pathinfo === '/contract_ended') {
                return array (  '_controller' => 'Hris\\DashboardBundle\\Controller\\ContractEndController::endedAction',  '_route' => 'hris_dashboard_contract_ended',);
            }

        }

        if (0 === strpos($pathinfo, '/re')) {
            if (0 === strpos($pathinfo, '/recruitment')) {
                if (0 === strpos($pathinfo, '/recruitment/app')) {
                    if (0 === strpos($pathinfo, '/recruitment/application')) {
                        // hris_applications_index
                        if ($pathinfo === '/recruitment/applications') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_applications_index;
                            }

                            return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::indexAction',  '_route' => 'hris_applications_index',);
                        }
                        not_hris_applications_index:

                        // hris_applications_add_form
                        if ($pathinfo === '/recruitment/application') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_applications_add_form;
                            }

                            return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::addFormAction',  '_route' => 'hris_applications_add_form',);
                        }
                        not_hris_applications_add_form:

                        // hris_applications_add_submit
                        if ($pathinfo === '/recruitment/application') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_applications_add_submit;
                            }

                            return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::addSubmitAction',  '_route' => 'hris_applications_add_submit',);
                        }
                        not_hris_applications_add_submit:

                        // hris_applications_edit_form
                        if (preg_match('#^/recruitment/application/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_applications_edit_form;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_edit_form')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::editFormAction',));
                        }
                        not_hris_applications_edit_form:

                        // hris_applications_edit_submit
                        if (preg_match('#^/recruitment/application/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_hris_applications_edit_submit;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_edit_submit')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::editSubmitAction',));
                        }
                        not_hris_applications_edit_submit:

                        // hris_applications_delete
                        if (preg_match('#^/recruitment/application/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_applications_delete;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_delete')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::deleteAction',));
                        }
                        not_hris_applications_delete:

                    }

                    // hris_applications_edit_progress_form
                    if (0 === strpos($pathinfo, '/recruitment/appprogress') && preg_match('#^/recruitment/appprogress/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_applications_edit_progress_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_edit_progress_form')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::editProgressFormAction',));
                    }
                    not_hris_applications_edit_progress_form:

                    // hris_applications_print_form
                    if (0 === strpos($pathinfo, '/recruitment/app/print') && preg_match('#^/recruitment/app/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_applications_print_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_print_form')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::printAppAction',));
                    }
                    not_hris_applications_print_form:

                    // hris_applications_edit_progress_submit
                    if (0 === strpos($pathinfo, '/recruitment/appprogress') && preg_match('#^/recruitment/appprogress/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_applications_edit_progress_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_edit_progress_submit')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::editProgressSubmitAction',));
                    }
                    not_hris_applications_edit_progress_submit:

                    if (0 === strpos($pathinfo, '/recruitment/application')) {
                        if (0 === strpos($pathinfo, '/recruitment/applications')) {
                            // hris_applications_grid
                            if ($pathinfo === '/recruitment/applications/grid') {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_applications_grid;
                                }

                                return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::gridAction',  '_route' => 'hris_applications_grid',);
                            }
                            not_hris_applications_grid:

                            // hris_applications_print
                            if (0 === strpos($pathinfo, '/recruitment/applications/print') && preg_match('#^/recruitment/applications/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_applications_print;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_print')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::printAppAction',));
                            }
                            not_hris_applications_print:

                            // hris_applications_appoint_print
                            if (0 === strpos($pathinfo, '/recruitment/applications/appoint/print') && preg_match('#^/recruitment/applications/appoint/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_applications_appoint_print;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_appoint_print')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::printAppointAction',));
                            }
                            not_hris_applications_appoint_print:

                            // hris_applications_background_print
                            if (0 === strpos($pathinfo, '/recruitment/applications/background/print') && preg_match('#^/recruitment/applications/background/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_hris_applications_background_print;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_background_print')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::printBackgroundAction',));
                            }
                            not_hris_applications_background_print:

                        }

                        // hris_applications_status
                        if ($pathinfo === '/recruitment/application/status') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_applications_status;
                            }

                            return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::statusUpdateAction',  '_route' => 'hris_applications_status',);
                        }
                        not_hris_applications_status:

                    }

                }

                // hris_applications_get_cities
                if (0 === strpos($pathinfo, '/recruitment/cities') && preg_match('#^/recruitment/cities/(?P<parent_id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_applications_get_cities;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_get_cities')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::getChildLocationAction',));
                }
                not_hris_applications_get_cities:

                // hris_applications_get_cities2
                if (0 === strpos($pathinfo, '/recruitment/application/cities') && preg_match('#^/recruitment/application/cities/(?P<parent_id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_applications_get_cities2;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_get_cities2')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::getChildLocationAction',));
                }
                not_hris_applications_get_cities2:

                // hris_applications_get_states
                if (0 === strpos($pathinfo, '/recruitment/states') && preg_match('#^/recruitment/states/(?P<parent_id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_applications_get_states;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_get_states')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::getChildLocationAction',));
                }
                not_hris_applications_get_states:

                if (0 === strpos($pathinfo, '/recruitment/application')) {
                    // hris_applications_get_states2
                    if (0 === strpos($pathinfo, '/recruitment/application/states') && preg_match('#^/recruitment/application/states/(?P<parent_id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_applications_get_states2;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_applications_get_states2')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::getChildLocationAction',));
                    }
                    not_hris_applications_get_states2:

                    // hris_applications_ajax_add
                    if ($pathinfo === '/recruitment/application/ajax/add') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_applications_ajax_add;
                        }

                        return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::ajaxAddAction',  '_route' => 'hris_applications_ajax_add',);
                    }
                    not_hris_applications_ajax_add:

                }

                // hris_applications_ajax_filter
                if ($pathinfo === '/recruitment/jobtitles/ajax') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_hris_applications_ajax_filter;
                    }

                    return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ApplicationController::ajaxFilterVacancyAction',  '_route' => 'hris_applications_ajax_filter',);
                }
                not_hris_applications_ajax_filter:

                if (0 === strpos($pathinfo, '/recruitment/requisition')) {
                    // hris_requisition_index
                    if ($pathinfo === '/recruitment/requisitions') {
                        return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\RequisitionController::indexAction',  '_route' => 'hris_requisition_index',);
                    }

                    // hris_requisition_add_form
                    if ($pathinfo === '/recruitment/requisition') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_requisition_add_form;
                        }

                        return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\RequisitionController::addFormAction',  '_route' => 'hris_requisition_add_form',);
                    }
                    not_hris_requisition_add_form:

                    // hris_requisition_add_submit
                    if ($pathinfo === '/recruitment/requisition') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_requisition_add_submit;
                        }

                        return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\RequisitionController::addSubmitAction',  '_route' => 'hris_requisition_add_submit',);
                    }
                    not_hris_requisition_add_submit:

                    // hris_requisition_edit_form
                    if (preg_match('#^/recruitment/requisition/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_requisition_edit_form;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_requisition_edit_form')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\RequisitionController::editFormAction',));
                    }
                    not_hris_requisition_edit_form:

                    // hris_requisition_edit_submit
                    if (preg_match('#^/recruitment/requisition/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_hris_requisition_edit_submit;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_requisition_edit_submit')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\RequisitionController::editSubmitAction',));
                    }
                    not_hris_requisition_edit_submit:

                    // hris_requisition_delete
                    if (preg_match('#^/recruitment/requisition/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_hris_requisition_delete;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_requisition_delete')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\RequisitionController::deleteAction',));
                    }
                    not_hris_requisition_delete:

                    if (0 === strpos($pathinfo, '/recruitment/requisitions')) {
                        // hris_requisition_grid
                        if ($pathinfo === '/recruitment/requisitions/grid') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_requisition_grid;
                            }

                            return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\RequisitionController::gridAction',  '_route' => 'hris_requisition_grid',);
                        }
                        not_hris_requisition_grid:

                        // hris_requisition_print
                        if (0 === strpos($pathinfo, '/recruitment/requisitions/print') && preg_match('#^/recruitment/requisitions/print/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_hris_requisition_print;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'hris_requisition_print')), array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\RequisitionController::printReqAction',));
                        }
                        not_hris_requisition_print:

                    }

                }

                if (0 === strpos($pathinfo, '/recruitment/checklist')) {
                    // hris_checklist_index
                    if ($pathinfo === '/recruitment/checklist') {
                        return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ChecklistController::indexAction',  '_route' => 'hris_checklist_index',);
                    }

                    // hris_checklist_add_form
                    if ($pathinfo === '/recruitment/checklist/add') {
                        return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ChecklistController::addFormAction',  '_route' => 'hris_checklist_add_form',);
                    }

                    // hris_checklist_edit_form
                    if ($pathinfo === '/recruitment/checklists') {
                        return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ChecklistController::addFormAction',  '_route' => 'hris_checklist_edit_form',);
                    }

                    // hris_checklist_print
                    if ($pathinfo === '/recruitment/checklist/print') {
                        return array (  '_controller' => 'Hris\\RecruitmentBundle\\Controller\\ChecklistController::printAction',  '_route' => 'hris_checklist_print',);
                    }

                }

            }

            if (0 === strpos($pathinfo, '/reports')) {
                // quadrant_report_index
                if (rtrim($pathinfo, '/') === '/reports') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_quadrant_report_index;
                    }

                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'quadrant_report_index');
                    }

                    return array (  '_controller' => 'Quadrant\\ReportBundle\\Controller\\ReportController::indexAction',  '_route' => 'quadrant_report_index',);
                }
                not_quadrant_report_index:

                // quadrant_report_submit
                if ($pathinfo === '/reports/') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_quadrant_report_submit;
                    }

                    return array (  '_controller' => 'Quadrant\\ReportBundle\\Controller\\ReportController::generateAction',  '_route' => 'quadrant_report_submit',);
                }
                not_quadrant_report_submit:

                // quadrant_report_csv
                if ($pathinfo === '/reports/csv') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_quadrant_report_csv;
                    }

                    return array (  '_controller' => 'Quadrant\\ReportBundle\\Controller\\ReportController::csvAction',  '_route' => 'quadrant_report_csv',);
                }
                not_quadrant_report_csv:

                // quadrant_report_get_filters
                if (0 === strpos($pathinfo, '/reports/filters') && preg_match('#^/reports/filters/(?P<report_id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_quadrant_report_get_filters;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'quadrant_report_get_filters')), array (  '_controller' => 'Quadrant\\ReportBundle\\Controller\\ReportController::getReportFiltersAction',));
                }
                not_quadrant_report_get_filters:

            }

            if (0 === strpos($pathinfo, '/resetting')) {
                // fos_user_resetting_request
                if ($pathinfo === '/resetting/request') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_fos_user_resetting_request;
                    }

                    return array (  '_controller' => 'fos_user.resetting.controller:requestAction',  '_route' => 'fos_user_resetting_request',);
                }
                not_fos_user_resetting_request:

                // fos_user_resetting_send_email
                if ($pathinfo === '/resetting/send-email') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_fos_user_resetting_send_email;
                    }

                    return array (  '_controller' => 'fos_user.resetting.controller:sendEmailAction',  '_route' => 'fos_user_resetting_send_email',);
                }
                not_fos_user_resetting_send_email:

                // fos_user_resetting_check_email
                if ($pathinfo === '/resetting/check-email') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_fos_user_resetting_check_email;
                    }

                    return array (  '_controller' => 'fos_user.resetting.controller:checkEmailAction',  '_route' => 'fos_user_resetting_check_email',);
                }
                not_fos_user_resetting_check_email:

                // fos_user_resetting_reset
                if (0 === strpos($pathinfo, '/resetting/reset') && preg_match('#^/resetting/reset/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                        goto not_fos_user_resetting_reset;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'fos_user_resetting_reset')), array (  '_controller' => 'fos_user.resetting.controller:resetAction',));
                }
                not_fos_user_resetting_reset:

            }

        }

        if (0 === strpos($pathinfo, '/profile')) {
            // fos_user_profile_show
            if (rtrim($pathinfo, '/') === '/profile') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fos_user_profile_show;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'fos_user_profile_show');
                }

                return array (  '_controller' => 'fos_user.profile.controller:showAction',  '_route' => 'fos_user_profile_show',);
            }
            not_fos_user_profile_show:

            // fos_user_profile_edit
            if ($pathinfo === '/profile/edit') {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_fos_user_profile_edit;
                }

                return array (  '_controller' => 'fos_user.profile.controller:editAction',  '_route' => 'fos_user_profile_edit',);
            }
            not_fos_user_profile_edit:

        }

        if (0 === strpos($pathinfo, '/registration')) {
            // fos_user_registration_register
            if (rtrim($pathinfo, '/') === '/registration') {
                if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                    goto not_fos_user_registration_register;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'fos_user_registration_register');
                }

                return array (  '_controller' => 'fos_user.registration.controller:registerAction',  '_route' => 'fos_user_registration_register',);
            }
            not_fos_user_registration_register:

            if (0 === strpos($pathinfo, '/registration/c')) {
                // fos_user_registration_check_email
                if ($pathinfo === '/registration/check-email') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_fos_user_registration_check_email;
                    }

                    return array (  '_controller' => 'fos_user.registration.controller:checkEmailAction',  '_route' => 'fos_user_registration_check_email',);
                }
                not_fos_user_registration_check_email:

                if (0 === strpos($pathinfo, '/registration/confirm')) {
                    // fos_user_registration_confirm
                    if (preg_match('#^/registration/confirm/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fos_user_registration_confirm;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fos_user_registration_confirm')), array (  '_controller' => 'fos_user.registration.controller:confirmAction',));
                    }
                    not_fos_user_registration_confirm:

                    // fos_user_registration_confirmed
                    if ($pathinfo === '/registration/confirmed') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fos_user_registration_confirmed;
                        }

                        return array (  '_controller' => 'fos_user.registration.controller:confirmedAction',  '_route' => 'fos_user_registration_confirmed',);
                    }
                    not_fos_user_registration_confirmed:

                }

            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
