<?php
/**
 * This class helps to create HTML elements in PHP
 */

class CcnHtmlObj implements JsonSerializable {

    private $tag_name;
    private $attrs;
    private $content;


    function __construct($tag_name, $attrs = [], $content = '') {
        $this->tag_name = $tag_name;
        $this->attrs = $attrs;
        $this->content = '';
        $this->append($content);
    }

    public function toString() {
        $s_attrs = ' ';
        foreach($this->attrs as $k => $v) {
            $s_attrs .= $k.'="'.str_replace('"', '\\"', $v).'" ';
        }
        $el = '<'.$this->tag_name.$s_attrs.'>';
        if (isset($this->attrs['_end_tag']) && $this->attrs['_end_tag']) return $el;
        return $el.$this->content.'</'.$this->tag_name.'>';
    }

    public function append($thing) {
        if (gettype($thing) == 'string') return $this->content .= $thing;
        if (is_array($thing)) return array_map(function($el) {$this->append($el);}, $thing);
        if (gettype($thing) == 'object' && get_class($thing) == 'CcnHtmlObj') return $this->content .= $thing->toString();
    }

    public function html($thing) {
        if (gettype($thing) == 'string') $this->content = $thing;
        if (is_array($thing)) return array_map(function($el) {$this->html($el);}, $thing);
        if (gettype($thing) == 'object' && get_class($thing) == 'CcnHtmlObj') return $this->content = $thing->toString();
    }

    public function jsonSerialize() {
        // because this class implements JsonSerializable, this function defines the way an object of this class is serialized with json_encode()
		return json_encode([
            'tag' => $this->tag_name,
            'attributes' => $this->attrs,
            'content' => $this->content,
        ]);
	}
}

?>