<?php
    class Tree {
        public $root;
        
        public function __construct() {
            $this->root = new TreeNode();
        }

        public function getRoot() {
            return $this->root;
        }
    }

    class TreeNode {
        public $value;
        public $parent;
        public $left;
        public $right;
        public $num;

        public function __construct() {
            $this->value = null;
            $this->parent = null;
            $this->left = null;
            $this->right = null;
            $this->num = null;
        }

        public function setValue($value) {
            $this->value = $value;
        }

        public function getValue() {
            return $this->value;
        }

        public function setParent($node) {
            $this->parent = $node;
        }

        public function getParent() {
            return $this->parent;
        }

        public function setLeft($node) {
            $this->left = $node;
        }

        public function getLeft() {
            return $this->left;
        }

        public function setRight($node) {
            $this->right = $node;
        }

        public function getRight() {
            return $this->right;
        }

        public function setNum($num) {
            $this->num = $num;
        }

        public function getNum() {
            return $this->num;
        }
    }

    $openBrackets = array('{', '[', '('); // parentesi aperte consentite
    $closeBrackets = array('}', ']', ')'); // parentesi chiuse consentite
    $operators = array('+', '-', '/', '*', '^'); // operatori matematici a disposizione

    /*
        EXAMPLES:
            - (((e ^ ln(x+5))) / 5.5) + PI
            - ((x+5) / (x - 11)) * log(x)
            - sin(x/4)
            - ((e ^ x) / (2 * x)) + ln(x^3)
    */
	
    /* INSERIRE FUNZIONE DA CALCOLARE */
    $esprOriginal = "1/x"; // espressione matematica di partenza
    $espr = str_replace(' ', '', $esprOriginal); // eliminazione spazi vuoti dall'espressione matematica

    $tree = new Tree(); // albero binario che rappresenta l'equazione matematica
    $currentNode = $tree->getRoot(); // nodo radice dell'albero

    $temp = ""; // inizializzazione variabile temporale
    for ($ind = 0; $ind < strlen($espr); $ind++) {
        // se si tratta di un numero (numero, PI, e) o della variabile x 
        if (!in_array($espr[$ind], $operators) and !in_array($espr[$ind], $openBrackets) and !in_array($espr[$ind], $closeBrackets)) {
            $temp .= $espr[$ind]; // contenazione dei caratteri fino alla lettura di una parentesi o di un operatore matematico
        } else {
            if ($temp !== "") {
                $nodoNumero = new TreeNode(); // creazione nuovo nodo
                if ($temp == 'x') { // incognita X
                    // $nodoNumero->setValue("x");
                    $nodoNumero->setNum("x");
                } else if ($temp == "e") { // numero di nepero
                    // $nodoNumero->setValue("e");
                    $nodoNumero->setNum("e");
                } else if ($temp == "PI") { // pi greco
                    // $nodoNumero->setValue("pi");
                    $nodoNumero->setNum("pi");
                } else if ($temp == "log") { // logaritmo base 10
                    $nodoNumero->setValue("log");
                } else if ($temp == "ln") { // logaritmo base e
                    $nodoNumero->setValue("ln");
                } else if ($temp == "sin") { // funzione sin
                    $nodoNumero->setValue("sin");
                } else { // numero e quindi possibile conversione a dato tipo float
                    $nodoNumero->setValue((float)$temp);
                    $nodoNumero->setNum((float)$temp);
                }

                if ($currentNode->getLeft() === null) // se il nodo corrente non ha alcun sottonodo nel lato sinistro
                    $currentNode->setLeft($nodoNumero); // sottonodo lato sinistro 
                else // se il nodo corrente ha già un sottonodo sinistro
                    $currentNode->setRight($nodoNumero); // sottofondo lato destro
                $nodoNumero->setParent($currentNode); // set parent al nodo appena creato

                if ($temp == "log" or $temp == "ln" or $temp == "sin") // modifica nodo corrente nel caso di operazione logaritmica 
                    $currentNode = $nodoNumero;
                $temp = ""; // azzeramento variabile temporanea
            }
            
            // se si tratta di un operatore matematico, tra quelli previsti
            if (in_array($espr[$ind], $operators)) {
                $currentNode->setValue($espr[$ind]); // set valore al nodo corrente
            } else if (in_array($espr[$ind], $openBrackets)) {
                // se si tratta di una parentesi aperta
                $nodoParentesi = new TreeNode(); // nuovo nodo
                $nodoParentesi->setParent($currentNode); // set parent del nodo creato

                if ($currentNode->getLeft() === null)
                    $currentNode->setLeft($nodoParentesi);
                else
                    $currentNode->setRight($nodoParentesi);
                $currentNode = $nodoParentesi;
            } else {
                // se si tratta di una parentesi chiusa
                $currentNode = $currentNode->getParent(); // modifica nodo corrente
            }
        }
    }
    if ($temp !==  "") {
        $nodoNumero = new TreeNode();
        if ($temp === "x") {
            $nodoNumero->setValue("x");
            $nodoNumero->setNum("x");
        } else if ($temp == "e") {
            $nodoNumero->setValue("e");
            $nodoNumero->setNum("e");
        } else if ($temp == "PI") { // pi greco
            $nodoNumero->setValue("pi");
            $nodoNumero->setNum("pi");
        } else if ($temp == "log") { // logaritmo base 10
            $nodoNumero->setValue("log");
        } else if ($temp == "ln") { // logaritmo base e
            $nodoNumero->setValue("ln");
        } else if ($temp == "sin") { // funzione seno
            $nodoNumero->setValue("sin");
        } else {
            $nodoNumero->setValue((float)$temp);
            $nodoNumero->setNum((float)$temp);
        }

        if ($currentNode->getLeft() == null)
            $currentNode->setLeft($nodoNumero);
        else
            $currentNode->setRight($nodoNumero);
        $nodoNumero->setParent($currentNode);
    }
    
    $root = $tree->getRoot();

    /* TEST */
    try {
        $x = 10;
        $ris = solve($root, $x);
    } catch (exception $e) {
        $ris = $e->getMessage();
    } finally {
        echo "{x = " . $x . ", y = " . $ris . "}<br><br>";
    }
    /* init($root); // init the root, before calc other values
    try {
        $x = 7;
        $ris = solve($root, $x);
    } catch (exception $e) {
        $ris = $e->getMessage();
    } finally {
        echo "{x = " . $x . ", y = " . $ris . "}<br><br>";
    } */
    
    function solve($node, $valX) {
        if ($node->getLeft() !== null and $node->getLeft()->getNum() === null) {
            return solve($node->getLeft(), $valX);
        } else {
            if ($node->getRight() !== null and $node->getRight()->getNum() === null) {
                return solve($node->getRight(), $valX);
            } else {
                $num1 = $node->getLeft()->getNum();
                if ($num1 === "x")
                    $num1 = (float)$valX;
                else if ($num1 === "e")
                    $num1 = M_E;
                else if ($num1 === "pi")
                    $num1 = M_PI;
                
                if (!is_null($node->getRight())) {
                    $num2 = $node->getRight()->getNum();
                    if ($num2 === "x")
                        $num2 = (float)$valX;
                    else if ($num2 === "e")
                        $num2 = M_E;
                    else if ($num2 === "pi")
                        $num2 = M_PI;

                    switch ($node->getValue()) {
                        case '+':
                            $ris = $num1 + $num2;
                            break;
                        case '-':
                            $ris = $num1 - $num2;
                            break;
                        case '*':
                            $ris = $num1 * $num2;
                            break;
                        case '/':
                            if ($num1 == 0 and $num2 == 0)
                                throw new Exception("Out of domain");
                            if ($num2 == 0)
                                throw new Exception("Out of domain");
                            $ris = $num1 / $num2;
                            break;
                        case '^':
                            $ris = pow($num1, $num2);
                            break;
                    }
                
                    $node->setNum($ris);
                } else {
                    if ($node->getValue() === "log") {
                        if ($node->getLeft()->getNum() <= 0)
                            throw new Exception("Out of domain");
                        $node->setNum(log($node->getLeft()->getNum(), 10));
                    } else if ($node->getValue() === "ln") {
                        if ($node->getLeft()->getNum() <= 0) {
                            throw new Exception("Out of domain");
                        }
                        $node->setNum(log($node->getLeft()->getNum(), M_E));
                    } else if ($node->getValue() == "sin") {
                        $node->setNum(sin(deg2rad($node->getLeft()->getNum()))); // conversione da gradi a radianti
                    } else
                        $node->setNum($num1);
                }

                if ($node->getParent() === null)
                    return $node->getNum();
                return solve($node->getParent(), $valX);
            }
        }
    }

    function init($node) {
        global $operators;

        if ($node->getLeft() !== null and ((in_array($node->getLeft()->getValue(), $operators) or $node->getLeft()->getValue() === "ln" or $node->getLeft()->getValue() === "log")) and $node->getLeft()->getNum() !== null) {
            return init($node->getLeft());
        } else {
            if ($node->getRight() !== null and ((in_array($node->getRight()->getValue(), $operators) or ($node->getRight()->getValue() === "ln" or $node->getRight()->getValue() === "log"))) and $node->getRight()->getNum() !== null) {
                return init($node->getRight());
            } else {
                $node->setNum(null);
                
                if ($node->getParent() === null)
                    return;
                return init($node->getParent());
            }
        }
    }
?>