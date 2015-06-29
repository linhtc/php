<?php

/* TwigBundle:Exception:exception_full.html.twig */
class __TwigTemplate_103e293ef398048e70b801bf92685da08a9f409e578153a6ccc254db0756c54d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("TwigBundle::layout.html.twig", "TwigBundle:Exception:exception_full.html.twig", 1);
        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "TwigBundle::layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_2f0efbda019358bf3a71cfe8a1762e00788ebe3381648fd5eaefdb83b6bd4cdc = $this->env->getExtension("native_profiler");
        $__internal_2f0efbda019358bf3a71cfe8a1762e00788ebe3381648fd5eaefdb83b6bd4cdc->enter($__internal_2f0efbda019358bf3a71cfe8a1762e00788ebe3381648fd5eaefdb83b6bd4cdc_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "TwigBundle:Exception:exception_full.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_2f0efbda019358bf3a71cfe8a1762e00788ebe3381648fd5eaefdb83b6bd4cdc->leave($__internal_2f0efbda019358bf3a71cfe8a1762e00788ebe3381648fd5eaefdb83b6bd4cdc_prof);

    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        $__internal_28657658f8ce2228f826b6bd2883804bc402eba027a6e71e2929bdfe438373ed = $this->env->getExtension("native_profiler");
        $__internal_28657658f8ce2228f826b6bd2883804bc402eba027a6e71e2929bdfe438373ed->enter($__internal_28657658f8ce2228f826b6bd2883804bc402eba027a6e71e2929bdfe438373ed_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "head"));

        // line 4
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('request')->generateAbsoluteUrl($this->env->getExtension('asset')->getAssetUrl("bundles/framework/css/exception.css")), "html", null, true);
        echo "\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />
";
        
        $__internal_28657658f8ce2228f826b6bd2883804bc402eba027a6e71e2929bdfe438373ed->leave($__internal_28657658f8ce2228f826b6bd2883804bc402eba027a6e71e2929bdfe438373ed_prof);

    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        $__internal_760de9e5c10b4411408cfeed1e167e27b5b18378b188324c88837a19262fd5f9 = $this->env->getExtension("native_profiler");
        $__internal_760de9e5c10b4411408cfeed1e167e27b5b18378b188324c88837a19262fd5f9->enter($__internal_760de9e5c10b4411408cfeed1e167e27b5b18378b188324c88837a19262fd5f9_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "title"));

        // line 8
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "message", array()), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, (isset($context["status_code"]) ? $context["status_code"] : $this->getContext($context, "status_code")), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, (isset($context["status_text"]) ? $context["status_text"] : $this->getContext($context, "status_text")), "html", null, true);
        echo ")
";
        
        $__internal_760de9e5c10b4411408cfeed1e167e27b5b18378b188324c88837a19262fd5f9->leave($__internal_760de9e5c10b4411408cfeed1e167e27b5b18378b188324c88837a19262fd5f9_prof);

    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
        $__internal_b7b5f42798a31b5a12f00f91dd25508a35a404e15cf9404ab79a2f45f52ef156 = $this->env->getExtension("native_profiler");
        $__internal_b7b5f42798a31b5a12f00f91dd25508a35a404e15cf9404ab79a2f45f52ef156->enter($__internal_b7b5f42798a31b5a12f00f91dd25508a35a404e15cf9404ab79a2f45f52ef156_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 12
        echo "    ";
        $this->loadTemplate("TwigBundle:Exception:exception.html.twig", "TwigBundle:Exception:exception_full.html.twig", 12)->display($context);
        
        $__internal_b7b5f42798a31b5a12f00f91dd25508a35a404e15cf9404ab79a2f45f52ef156->leave($__internal_b7b5f42798a31b5a12f00f91dd25508a35a404e15cf9404ab79a2f45f52ef156_prof);

    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:exception_full.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 12,  72 => 11,  58 => 8,  52 => 7,  42 => 4,  36 => 3,  11 => 1,);
    }
}
