```php
<?php

// ==========================================
// BASE MODEL CLASS
// ==========================================
namespace App\Models;

abstract class Model {
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $casts = [];
    protected $attributes = [];
    
    protected static $connection;
    
    public static function setConnection($pdo) {
        self::$connection = $pdo;
    }
    
    protected function getConnection() {
        return self::$connection;
    }
    
    public static function all() {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare("SELECT * FROM {$instance->table}");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    public static function find($id) {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare("SELECT * FROM {$instance->table} WHERE {$instance->primaryKey} = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }
    
    public static function where($column, $operator, $value = null) {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $instance = new static();
        $stmt = $instance->getConnection()->prepare("SELECT * FROM {$instance->table} WHERE $column $operator ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    public static function create(array $data) {
        $instance = new static();
        $instance->fill($data);
        $instance->save();
        return $instance;
    }
    
    public function fill(array $data) {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable) && !in_array($key, $this->guarded)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }
    
    public function save() {
        if (isset($this->attributes[$this->primaryKey])) {
            return $this->update();
        }
        return $this->insert();
    }
    
    protected function insert() {
        $columns = implode(', ', array_keys($this->attributes));
        $placeholders = implode(', ', array_fill(0, count($this->attributes), '?'));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute(array_values($this->attributes));
        
        $this->attributes[$this->primaryKey] = $this->getConnection()->lastInsertId();
        return true;
    }
    
    protected function update() {
        $sets = [];
        $values = [];
        
        foreach ($this->attributes as $key => $value) {
            if ($key !== $this->primaryKey) {
                $sets[] = "$key = ?";
                $values[] = $value;
            }
        }
        
        $values[] = $this->attributes[$this->primaryKey];
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = ?";
        $stmt = $this->getConnection()->prepare($sql);
        return $stmt->execute($values);
    }
    
    public function delete() {
        if (!isset($this->attributes[$this->primaryKey])) {
            return false;
        }
        
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->getConnection()->prepare($sql);
        return $stmt->execute([$this->attributes[$this->primaryKey]]);
    }
    
    public function __get($key) {
        return $this->attributes[$key] ?? null;
    }
    
    public function __set($key, $value) {
        $this->attributes[$key] = $value;
    }
    
    public function toArray() {
        $array = $this->attributes;
        foreach ($this->hidden as $key) {
            unset($array[$key]);
        }
        return $array;
    }
}

// ==========================================
// BASE REQUEST CLASS
// ==========================================
namespace App\Http\Requests;

abstract class Request {
    protected $data = [];
    protected $errors = [];
    
    abstract public function rules();
    
    public function __construct(array $data = []) {
        $this->data = $data ?: array_merge($_GET, $_POST, json_decode(file_get_contents('php://input'), true) ?: []);
    }
    
    public function validate() {
        $rules = $this->rules();
        
        foreach ($rules as $field => $ruleSet) {
            $ruleArray = explode('|', $ruleSet);
            
            foreach ($ruleArray as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        
        return empty($this->errors);
    }
    
    protected function applyRule($field, $rule) {
        $value = $this->data[$field] ?? null;
        
        if (strpos($rule, ':') !== false) {
            list($ruleName, $parameter) = explode(':', $rule, 2);
        } else {
            $ruleName = $rule;
            $parameter = null;
        }
        
        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->errors[$field][] = "The $field field is required.";
                }
                break;
            
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "The $field must be a valid email address.";
                }
                break;
            
            case 'min':
                if (!empty($value) && strlen($value) < $parameter) {
                    $this->errors[$field][] = "The $field must be at least $parameter characters.";
                }
                break;
            
            case 'max':
                if (!empty($value) && strlen($value) > $parameter) {
                    $this->errors[$field][] = "The $field may not be greater than $parameter characters.";
                }
                break;
            
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->errors[$field][] = "The $field must be a number.";
                }
                break;
            
            case 'string':
                if (!empty($value) && !is_string($value)) {
                    $this->errors[$field][] = "The $field must be a string.";
                }
                break;
        }
    }
    
    public function validated() {
        if (!$this->validate()) {
            throw new \Exception(json_encode($this->errors));
        }
        return array_intersect_key($this->data, $this->rules());
    }
    
    public function errors() {
        return $this->errors;
    }
    
    public function get($key, $default = null) {
        return $this->data[$key] ?? $default;
    }
    
    public function all() {
        return $this->data;
    }
}

// ==========================================
// BASE CONTROLLER CLASS
// ==========================================
namespace App\Http\Controllers;

abstract class Controller {
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function view($template, $data = []) {
        extract($data);
        include __DIR__ . "/../../views/$template.php";
    }
    
    protected function redirect($url, $status = 302) {
        http_response_code($status);
        header("Location: $url");
        exit;
    }
    
    protected function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
}

// ==========================================
// EXAMPLE: USER MODEL
// ==========================================
namespace App\Models;

class User extends Model {
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];
    protected $guarded = ['id'];
    
    // Custom methods
    public function posts() {
        return Post::where('user_id', '=', $this->id);
    }
    
    public static function findByEmail($email) {
        return static::where('email', '=', $email)[0] ?? null;
    }
}

// ==========================================
// EXAMPLE: POST MODEL
// ==========================================
namespace App\Models;

class Post extends Model {
    protected $table = 'posts';
    protected $fillable = ['user_id', 'title', 'content', 'published'];
    protected $guarded = ['id'];
    
    public function user() {
        return User::find($this->user_id);
    }
}

// ==========================================
// EXAMPLE: STORE USER REQUEST
// ==========================================
namespace App\Http\Requests;

class StoreUserRequest extends Request {
    public function rules() {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ];
    }
}

// ==========================================
// EXAMPLE: UPDATE POST REQUEST
// ==========================================
namespace App\Http\Requests;

class UpdatePostRequest extends Request {
    public function rules() {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published' => 'numeric'
        ];
    }
}

// ==========================================
// EXAMPLE: USER CONTROLLER
// ==========================================
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller {
    public function index() {
        $users = User::all();
        return $this->json(['users' => array_map(fn($u) => $u->toArray(), $users)]);
    }
    
    public function show($id) {
        $user = User::find($id);
        
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }
        
        return $this->json(['user' => $user->toArray()]);
    }
    
    public function store(StoreUserRequest $request) {
        if (!$request->validate()) {
            return $this->json(['errors' => $request->errors()], 422);
        }
        
        $data = $request->validated();
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $user = User::create($data);
        
        return $this->json(['user' => $user->toArray()], 201);
    }
    
    public function update($id, StoreUserRequest $request) {
        $user = User::find($id);
        
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }
        
        if (!$request->validate()) {
            return $this->json(['errors' => $request->errors()], 422);
        }
        
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $user->fill($data);
        $user->save();
        
        return $this->json(['user' => $user->toArray()]);
    }
    
    public function destroy($id) {
        $user = User::find($id);
        
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }
        
        $user->delete();
        
        return $this->json(['message' => 'User deleted successfully']);
    }
}

// ==========================================
// EXAMPLE: POST CONTROLLER
// ==========================================
namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller {
    public function index() {
        $posts = Post::all();
        return $this->json(['posts' => array_map(fn($p) => $p->toArray(), $posts)]);
    }
    
    public function show($id) {
        $post = Post::find($id);
        
        if (!$post) {
            return $this->json(['error' => 'Post not found'], 404);
        }
        
        $user = $post->user();
        $postData = $post->toArray();
        $postData['user'] = $user ? $user->toArray() : null;
        
        return $this->json(['post' => $postData]);
    }
    
    public function store(UpdatePostRequest $request) {
        if (!$request->validate()) {
            return $this->json(['errors' => $request->errors()], 422);
        }
        
        $post = Post::create($request->validated());
        
        return $this->json(['post' => $post->toArray()], 201);
    }
    
    public function update($id, UpdatePostRequest $request) {
        $post = Post::find($id);
        
        if (!$post) {
            return $this->json(['error' => 'Post not found'], 404);
        }
        
        if (!$request->validate()) {
            return $this->json(['errors' => $request->errors()], 422);
        }
        
        $post->fill($request->validated());
        $post->save();
        
        return $this->json(['post' => $post->toArray()]);
    }
    
    public function destroy($id) {
        $post = Post::find($id);
        
        if (!$post) {
            return $this->json(['error' => 'Post not found'], 404);
        }
        
        $post->delete();
        
        return $this->json(['message' => 'Post deleted successfully']);
    }
}

// ==========================================
// USAGE EXAMPLE / BOOTSTRAP
// ==========================================

// Initialize database connection
$pdo = new \PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

\App\Models\Model::setConnection($pdo);

// Example routing (you'd normally use a router)
/*
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/users' && $method === 'GET') {
    $controller = new \App\Http\Controllers\UserController();
    $controller->index();
}

if (preg_match('/^\/users\/(\d+)$/', $uri, $matches) && $method === 'GET') {
    $controller = new \App\Http\Controllers\UserController();
    $controller->show($matches[1]);
}

if ($uri === '/users' && $method === 'POST') {
    $request = new \App\Http\Requests\StoreUserRequest();
    $controller = new \App\Http\Controllers\UserController();
    $controller->store($request);
}
*/

```