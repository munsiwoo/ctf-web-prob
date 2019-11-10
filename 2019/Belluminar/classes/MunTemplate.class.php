<?php
error_reporting(0);

class MunTemplate {
    private $template_path;

    function __construct($template_path) {
        $this->template_path = $template_path;
    }

    private function process_var($template) {
        $parse_regexp = "/\@var(?:\s+)?(?<mun>\(((?:[^()]|(?&mun))*)\))/mis";
        $replace_code = '<?php echo $2; ?>';
        $retval = preg_replace($parse_regexp, $replace_code, $template);

        return $retval;
    }

    private function process_for($template) {
        $start_regexp = "/\@mun\s+for(?:\s+)?(?<mun>\(((?:[^()]|(?&mun))*)\))/mis";
        $end_regexp = "/\@endfor(?:\s+)?$/mis";

        $template = preg_replace($start_regexp, '<?php for($2) : ?>', $template);
        $retval = preg_replace($end_regexp, '<?php endfor; ?>', $template);

        return $retval;
    }

    private function process_foreach($template) {
        $start_regexp = "/\@mun\s+foreach(?:\s+)?(?<mun>\(((?:[^()]|(?&mun))*)\))/mis";
        $end_regexp = "/\@endforeach(?:\s+)?$/mis";

        $template = preg_replace($start_regexp, '<?php foreach($2) : ?>', $template);
        $retval = preg_replace($end_regexp, '<?php endforeach; ?>', $template);

        return $retval;
    }

    private function process_if($template) {
        $if_regexp = "/\@mun\s+if(?:\s+)?(?<mun>\(((?:[^()]|(?&mun))*)\))/mis";
        $elif_regexp = "/\@mun\s+elif(?:\s+)?(?<mun>\(((?:[^()]|(?&mun))*)\))/mis";
        $else_regexp = "/\@mun\s+else/mis";
        $end_regexp = "/\@endif/mis";

        $template = preg_replace($if_regexp, '<?php if($2) : ?>', $template);
        $template = preg_replace($elif_regexp, '<?php elseif($2) : ?>', $template);
        $template = preg_replace($else_regexp, '<?php else : ?>', $template);
        $retval = preg_replace($end_regexp, '<?php endif; ?>', $template);

        return $retval;
    }
    
    private function remove_php_tag($template) {
        $retval = preg_replace('/(<\?(?!xml))/', '&lt;?', $template);
        return $retval;
    }

    public function render_template($template, $vars=[], $debug_mode=0, $php_tag=0) {
        $error_report  = '<?php error_reporting(';
        $error_report .= $debug_mode ? 'E_ALL); ?>' : '0); ?>';

        foreach($vars as $var_name=>$value)
            ${$var_name} = $value; // ${variable_name} = value;

        $exec_code = file_get_contents($this->template_path.'/'.$template);
        if(!$php_tag)
            $exec_code = $this->remove_php_tag($exec_code);

        $exec_code = $this->process_for($exec_code);
        $exec_code = $this->process_foreach($exec_code);
        $exec_code = $this->process_if($exec_code);
        $exec_code = $this->process_var($exec_code);
        $exec_code = $error_report.$exec_code;
        
        eval("?>$exec_code");
        return true;
    }

    public function render_template_string($exec_code, $vars=[], $debug_mode=0, $php_tag=0) {
        $error_report  = '<?php error_reporting(';
        $error_report .= $debug_mode ? 'E_ALL); ?>' : '0); ?>';

        foreach($vars as $var_name=>$value)
            ${$var_name} = $value; // ${variable_name} = value;

        if(!$php_tag)
            $exec_code = $this->remove_php_tag($exec_code);

        $exec_code = $this->process_for($exec_code);
        $exec_code = $this->process_foreach($exec_code);
        $exec_code = $this->process_if($exec_code);
        $exec_code = $this->process_var($exec_code);
        $exec_code = $error_report.$exec_code;
        
        eval("?>$exec_code");
        return true;
    }
}
