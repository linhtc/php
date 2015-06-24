<?php

/* TwigBundle:Exception:exception_full.html.twig */
class __TwigTemplate_a86a58fbc65041ab5f418efee51cb8eb8e123de2260f3ad20f3406f159784798 extends Twig_Template
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
        $__internal_0f6c3d4a0996a1b3305fb4dffb027014ac5f7a0e8d15d51575b196a76d984d56 = $this->env->getExtension("native_profiler");
        $__internal_0f6c3d4a0996a1b3305fb4dffb027014ac5f7a0e8d15d51575b196a76d984d56->enter($__internal_0f6c3d4a0996a1b3305fb4dffb027014ac5f7a0e8d15d51575b196a76d984d56_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "TwigBundle:Exception:exception_full.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_0f6c3d4a0996a1b3305fb4dffb027014ac5f7a0e8d15d51575b196a76d984d56->leave($__internal_0f6c3d4a0996a1b3305fb4dffb027014ac5f7a0e8d15d51575b196a76d984d56_prof);

    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        $__internal_e7814c484afcdf3ff8d28d7565cca7afd34d4716af15ad7c76e8de2bcb936e9d = $this->env->getExtension("native_profiler");
        $__internal_e7814c484afcdf3ff8d28d7565cca7afd34d4716af15ad7c76e8de2bcb936e9d->enter($__internal_e7814c484afcdf3ff8d28d7565cca7afd34d4716af15ad7c76e8de2bcb936e9d_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "head"));

        // line 4
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('request')->generateAbsoluteUrl($this->env->getExtension('asset')->getAssetUrl("bundles/framework/css/exception.css")), "html", null, true);
        echo "\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />
";
        
        $__internal_e7814c484afcdf3ff8d28d7565cca7afd34d4716af15ad7c76e8de2bcb936e9d->leave($__internal_e7814c484afcdf3ff8d28d7565cca7afd34d4716af15ad7c76e8de2bcb936e9d_prof);

    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        $__internal_b35de5065fec98e79c748095640d2132742ba0e8b11a44d9ec95177fe500933f = $this->env->getExtension("native_profiler");
        $__internal_b35de5065fec98e79c748095640d2132742ba0e8b11a44d9ec95177fe500933f->enter($__internal_b35de5065fec98e79c748095640d2132742ba0e8b11a44d9ec95177fe500933f_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "title"));

        // line 8
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "message", array()), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, (isset($context["status_code"]) ? $context["status_code"] : $this->getContext($context, "status_code")), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, (isset($context["status_text"]) ? $context["status_text"] : $this->getContext($context, "status_text")), "html", null, true);
        echo ")
";
        
        $__internal_b35de5065fec98e79c748095640d2132742ba0e8b11a44d9ec95177fe500933f->leave($__internal_b35de5065fec98e79c748095640d2132742ba0e8b11a44d9ec95177fe500933f_prof);

    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
        $__internal_b43ca879ef0b88d10566a35d6fffa6997c8b1f593bd050e4bc6a8dba54dfa21b = $this->env->getExtension("native_profiler");
        $__internal_b43ca879ef0b88d10566a35d6fffa6997c8b1f593bd050e4bc6a8dba54dfa21b->enter($__internal_b43ca879ef0b88d10566a35d6fffa6997c8b1f593bd050e4bc6a8dba54dfa21b_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 12
        echo "    ";
        $this->loadTemplate("TwigBundle:Exception:exception.html.twig", "TwigBundle:Exception:exception_full.html.twig", 12)->display($context);
        
        $__internal_b43ca879ef0b88d10566a35d6fffa6997c8b1f593bd050e4bc6a8dba54dfa21b->leave($__internal_b43ca879ef0b88d10566a35d6fffa6997c8b1f593bd050e4bc6a8dba54dfa21b_prof);

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
