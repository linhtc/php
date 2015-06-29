<?php

/* TwigBundle:Exception:traces.txt.twig */
class __TwigTemplate_05aa14a161711937f81eb2fed3aa091bfd47a2d608a0f02b04de5f98a9e38b5a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_24fb526ccdcb93828779afd3f4106f1e4d8ca68dab467a024f8217b473fb30aa = $this->env->getExtension("native_profiler");
        $__internal_24fb526ccdcb93828779afd3f4106f1e4d8ca68dab467a024f8217b473fb30aa->enter($__internal_24fb526ccdcb93828779afd3f4106f1e4d8ca68dab467a024f8217b473fb30aa_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "TwigBundle:Exception:traces.txt.twig"));

        // line 1
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "trace", array()))) {
            // line 2
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "trace", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["trace"]) {
                // line 3
                $this->loadTemplate("TwigBundle:Exception:trace.txt.twig", "TwigBundle:Exception:traces.txt.twig", 3)->display(array("trace" => $context["trace"]));
                // line 4
                echo "
";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['trace'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        
        $__internal_24fb526ccdcb93828779afd3f4106f1e4d8ca68dab467a024f8217b473fb30aa->leave($__internal_24fb526ccdcb93828779afd3f4106f1e4d8ca68dab467a024f8217b473fb30aa_prof);

    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:traces.txt.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  30 => 4,  28 => 3,  24 => 2,  22 => 1,);
    }
}
