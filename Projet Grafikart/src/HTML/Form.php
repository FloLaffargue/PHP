<?php 

namespace App\HTML;

class Form {

    private $data;
    private $errors;

    public function __construct($data, array $errors) 
    {
        $this->data = $data;
        $this->errors = $errors;
    }
    
    private function getValue(string $key) {
        
        if(is_array($this->data)) {
            return $this->data[$key] ?? null;   
        }
        
        // Je remplace les _ par un espace (donne "created at")
        // Je met une MAJ sur chaque mot (donne "Created At)
        // Je remplace les espace par "rien" (donne CreatedAt)
        $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
        $value = $this->data->$method();
        if($value instanceof \DateTime) {
            $value = $value->format('Y-m-d H:i:s');
        }

        return $value;


    }

    private function getInputClass(string $key) {

        $inputClass = 'form-control';
        if (isset($this->errors[$key])) {
            $inputClass .= ' is-invalid';
        }
        return $inputClass;
    }

    private function getInvalidFeedback(string $key): string {

        if (isset($this->errors[$key])) {

            if(is_array($this->errors[$key])) {
                return implode('</br>',$this->errors[$key]);
            } else {
                return $this->errors[$key];
            }
        
        }
        return '';
    } 

    public function input(string $key, string $label): string {
        
        $inputClass      = $this->getInputClass($key);
        $invalidFeedback = $this->getInvalidFeedback($key);
        $value           = $this->getValue($key);
        $type = ($key == 'password') ? 'password' : 'text'; 
        return <<<HTML
        
        <div class="form-group">
            <label for="field{key}">$label</label>
            <input id="field{key}" type="{$type}" class="{$inputClass}" name=$key value="$value" >
            <div class="invalid-feedback">
                    $invalidFeedback
            </div>
        </div>

HTML;

    }

    public function textarea(string $key, string $label): string {

        $inputClass      = $this->getInputClass($key);
        $invalidFeedback = $this->getInvalidFeedback($key);
        $value           = $this->getValue($key);

        return <<<HTML
        
        <div class="form-group">
            <label for="field{key}">$label</label>
            <textarea id="field{key}" type="text" class="{$inputClass}" name=$key required>$value</textarea>
            <div class="invalid-feedback">
                    $invalidFeedback
            </div>
        </div>

HTML;
    }

    public function select(string $key, string $label, array $options = []): string
    {
        $inputClass      = $this->getInputClass($key);
        $invalidFeedback = $this->getInvalidFeedback($key);
        $value           = $this->getValue($key);
        
        $optionsHTML = [];

        foreach($options as $k => $v) {

            $selected = in_array($k, $value) ? 'selected' : '';
            $optionsHTML[] = "<option value=\"$k\" $selected>$v</option>";
        }
        $optionsHTML = implode('',$optionsHTML);
        return <<<HTML
        
        <div class="form-group">
            <label for="field{key}">$label</label>

            <select id="field{key}" type="text" class="{$inputClass}" name={$key}[] required multiple>
                $optionsHTML
            </select>

            <div class="invalid-feedback">
                    $invalidFeedback
            </div>
        </div>

HTML;

    }

    public function file(string $key, string $label): string {
        
        $inputClass      = $this->getInputClass($key);
        $invalidFeedback = $this->getInvalidFeedback($key);
        
        return <<<HTML
        
        <div class="form-group">
            <label for="field{key}">$label</label>
            <input id="field{key}" type="file" class="{$inputClass}" name=$key>
            <div class="invalid-feedback">
                    $invalidFeedback
            </div>
        </div>

HTML;

    }

}