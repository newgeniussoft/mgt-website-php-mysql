<?php

require_once __DIR__ . '/../../models/Model.php';
require_once 'Controller.php';

class MoreController extends Controller
{
    /**
     * @var array
     */
    private $models;

    public function __construct($language = null)
    {
        parent::__construct($language);
        $this->models = $this->getModels();
    }

    /**
     * List all models
     *
     * @return void
     */
    public function index($model = null)
    {
        if (isset($model)) {
            $this->show($model);
            return;
        }
        
        $user = $_SESSION['user'];
        echo $this->view('admin.models.all', [
            'user' => $user,
            'models' => $this->models,
        ]);
    }

    /**
     * Update a row for a specific model
     *
     * @param string $model
     * @param int $id
     *
     * @return void
     */
    public function update($model, $id)
    {
        $instance = new $model();
        $data = $_POST;
        unset($data['id']); // In case id is in the form
        $instance->update($id, $data);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * Show table for a specific model
     *
     * @param string $model
     *
     * @return void
     */
    public function show($model)
    {
        $instance = new $model();
        $table = $instance->table_name;
        $db = Database::getInstance()->getConn();
        $columns = [];
        $stmt = $db->query("SHOW COLUMNS FROM `$table`");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $columns[] = $row['Field'];
        }
        $rows = $instance->all();
        
        $user = $_SESSION['user'];
        echo $this->view('admin.models.show', [
            'user' => $user,
            'model' => $model,
            'columns' => $columns,
            'rows' => $rows,
        ]);
    }

    /**
     * Get all models
     *
     * @return array
     */
    private function getModels()
    {
        $models = [];
        $modelFiles = glob(__DIR__ . '/../../models/*.php');
        foreach ($modelFiles as $file) {
            $modelName = basename($file, '.php');
            if ($modelName !== 'Model') {
                require_once $file;
                $models[$modelName] = [
                    'name' => $modelName
                ];
            }
        }
        return $models;
    }
}
