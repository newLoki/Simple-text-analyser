<?php

class parser {
    protected $_patterns = array(
        'numbers'           => '/^-?\d+(\.\d+)?/',
        'articles'          => '/(the|The|A|a|An|an|)$/',
        'adjectives'        => '/un\w*/',
        'possesiv_nouns'    => '/\w*\'s$/',
        'plural_nouns'      => '/\w*s$/',
        'gerunds'           => '/\w*ing$/',
        'past_verbs'        => '/\w*ed$/',
        'comma'             => '/,/',
        'punctation'        => '/(!|\?|\.|\'|\'\'|)$/',
        'other'             => '/\w*/'
    );

    protected $_matches = array(
        'numbers'           => 0,
        'articles'          => 0,
        'adjectives'        => 0,
        'possesiv_nouns'    => 0,
        'plural_nouns'      => 0,
        'gerunds'           => 0,
        'past_verbs'        => 0,
        'comma'             => 0,
        'punctation'        => 0,
        'other'             => 0
    );

    protected $_frequency = array(
        'numbers'           => 0,
        'articles'          => 0,
        'adjectives'        => 0,
        'possesiv_nouns'    => 0,
        'plural_nouns'      => 0,
        'gerunds'           => 0,
        'past_verbs'        => 0,
        'comma'             => 0,
        'punctation'        => 0,
        'other'             => 0
    );

    protected $_wordNumbers = 0;

    public function run($_text)
    {
        $words = str_word_count($_text, 1);
        $this->_wordNumbers = count($words);
        $this->parse($words);
        $this->calculateFrequency();
    }

    protected function calculateFrequency()
    {
        $_wordCount = $this->_wordNumbers;
        foreach($this->_matches as $type => $matchCount) {
            if($matchCount > 0) {
                $this->_frequency[$type] = ($matchCount/$_wordCount) * 100;
            } 
        }
    }

    /*
     * Zerlegt einen Text in seinen einzelnen Wšrter
     */
    protected function parse(array $words)
    {
        //iterate over every word and check which pattern matches
        //add one to matches for this type

        //$words = preg_split('/[\s,]+/', $_text);
        foreach($words as $word) {
            foreach($this->_patterns as $type => $patern) {
                if(1 === preg_match($patern, $word)) {
                    $this->_matches[$type] += 1;
                }
                
            }
        }
    }

    public function getJSONOutput()
    {
        $output = new stdClass();
        $output->format = '%';
        $output->totalWords = $this->_wordNumbers;
        $output->frequency = $this->_frequency;
        $output->matches = $this->_matches;

        return json_encode($output);
    }
}