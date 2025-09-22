<?php


// 1. DATABASE CONNECTION CLASS
class Database {
     private $host = '127.0.0.1';
    private $dbname = 'ig';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}

// 2. comments CLASS - CRUD OPERATIONS
class comments {
    private $pdo;
    private $table = 'comments';

    public function __construct($database) {
        $this->pdo = $database->getConnection();
    }

    /**
     * Add a new comments
     */
    public function addcomments($post_id, $user_id, $comments_text) {
        try {
            // Validasi input
            if (empty(trim($comments_text))) {
                return [
                    'success' => false,
                    'message' => 'comment cannot be empty'
                ];
            }

            $query = "INSERT INTO {$this->table} (post_id, user_id, comments) VALUES (:post_id, :user_id, :comments)";
            $stmt = $this->pdo->prepare($query);
            
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':comments', trim($comments_text), PDO::PARAM_STR);
            
            $result = $stmt->execute();
            
            return [
                'success' => $result,
                'comments_id' => $this->pdo->lastInsertId(),
                'message' => $result ? 'comments added successfully' : 'Failed to add comments'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update a comments
     */
    public function updatecomments($comments_id, $user_id, $new_comments_text) {
        try {
            // Validasi input
            if (empty(trim($new_comments_text))) {
                return [
                    'success' => false,
                    'message' => 'comments cannot be empty'
                ];
            }

            $query = "UPDATE {$this->table} 
                     SET comments = :comments, updated_at = CURRENT_TIMESTAMP 
                     WHERE id = :comments_id AND user_id = :user_id";
            $stmt = $this->pdo->prepare($query);
            
            $stmt->bindParam(':comments', trim($new_comments_text), PDO::PARAM_STR);
            $stmt->bindParam(':comment_id', $comments_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            $affected_rows = $stmt->rowCount();
            
            return [
                'success' => $result && $affected_rows > 0,
                'message' => $affected_rows > 0 ? 'comments updated successfully' : 'comments not found or unauthorized'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete a comments
     */
    public function deletecomment($comments_id, $user_id) {
        try {
            $query = "DELETE FROM {$this->table} WHERE id = :comments_id AND user_id = :user_id";
            $stmt = $this->pdo->prepare($query);
            
            $stmt->bindParam(':comments_id', $comments_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            $affected_rows = $stmt->rowCount();
            
            return [
                'success' => $result && $affected_rows > 0,
                'message' => $affected_rows > 0 ? 'comments deleted successfully' : 'comment not found or unauthorized'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get a single comments by ID
     */
    public function getcomments($comments_id) {
        try {
            $query = "SELECT c.*, u.username, u.email, p.title as post_title
                     FROM {$this->table} c
                     JOIN users u ON c.user_id = u.id
                     JOIN posts p ON c.post_id = p.id
                     WHERE c.id = :comments_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':comments_id', $comments_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Get all comments for a specific post
     */
    public function getcommentsByPost($post_id, $limit = 10, $offset = 0, $order = 'ASC') {
        try {
            $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
            
            $query = "SELECT c.*, u.username, u.email
                     FROM {$this->table} c
                     JOIN users u ON c.user_id = u.id
                     WHERE c.post_id = :post_id
                     ORDER BY c.created_at {$order}
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get comments by a specific user
     */
    public function getcommentsByUser($user_id, $limit = 10, $offset = 0) {
        try {
            $query = "SELECT c.*, p.title as post_title
                     FROM {$this->table} c
                     JOIN posts p ON c.post_id = p.id
                     WHERE c.user_id = :user_id
                     ORDER BY c.created_at DESC
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get comments count for a post
     */
    public function getcommentsCount($post_id) {
        try {
            $query = "SELECT COUNT(*) FROM {$this->table} WHERE post_id = :post_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Search comments by keyword
     */
    public function searchcomments($keyword, $limit = 10, $offset = 0) {
        try {
            $keyword = '%' . $keyword . '%';
            
            $query = "SELECT c.*, u.username, p.title as post_title
                     FROM {$this->table} c
                     JOIN users u ON c.user_id = u.id
                     JOIN posts p ON c.post_id = p.id
                     WHERE c.coment LIKE :keyword
                     ORDER BY c.created_at DESC
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get recent comments across all posts
     */
    public function getRecentcomments($limit = 10) {
        try {
            $query = "SELECT c.*, u.username, p.title as post_title
                     FROM {$this->table} c
                     JOIN users u ON c.user_id = u.id
                     JOIN posts p ON c.post_id = p.id
                     ORDER BY c.created_at DESC
                     LIMIT :limit";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get most active comments
     */
    public function getMostActivecomments($limit = 10) {
        try {
            $query = "SELECT u.id, u.username, u.email, COUNT(c.id) as comment_count
                     FROM users u
                     JOIN {$this->table} c ON u.id = c.user_id
                     GROUP BY u.id, u.username, u.email
                     ORDER BY comment_count DESC
                     LIMIT :limit";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get posts with most comments
     */
    public function getMostcommenstedPosts($limit = 10) {
        try {
            $query = "SELECT p.*, COUNT(c.id) as comments_count
                     FROM posts p
                     LEFT JOIN {$this->table} c ON p.id = c.post_id
                     GROUP BY p.id
                     ORDER BY comments_count DESC, p.created_at DESC
                     LIMIT :limit";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get comment analytics
     */
    public function getcommentsAnalytics($days = 30) {
        try {
            $query = "SELECT 
                        DATE(created_at) as date,
                        COUNT(*) as daily_comments
                     FROM {$this->table}
                     WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL :days DAY)
                     GROUP BY DATE(created_at)
                     ORDER BY date DESC";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':days', $days, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}

// 3. comment API CLASS - For AJAX requests
class commentAPI {
    private $comments;

    public function __construct($comments) {
        $this->comment = $comments;
    }

    public function handleRequest() {
        // Set JSON header
        header('Content-Type: application/json');

        // Get request method
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'POST':
                $this->handlePost();
                break;
            case 'PUT':
                $this->handlePut();
                break;
            case 'DELETE':
                $this->handleDelete();
                break;
            case 'GET':
                $this->handleGet();
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }
    }

    private function handlePost() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['post_id']) || !isset($input['user_id']) || !isset($input['comments'])) {
            http_response_code(400);
            echo json_encode(['error' => 'post_id, user_id, and comments are required']);
            return;
        }

        $result = $this->comments->addcomments($input['post_id'], $input['user_id'], $input['comments']);
        
        if ($result['success']) {
            $comment_data = $this->comments->getcomments($result['comments_id']);
            $comment_count = $this->comments->getcommentsCount($input['post_id']);
            
            echo json_encode([
                'success' => true,
                'message' => $result['message'],
                'comment' => $comment_data,
                'comment_count' => $comment_count
            ]);
        } else {
            http_response_code(400);
            echo json_encode($result);
        }
    }

    private function handlePut() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['comments_id']) || !isset($input['user_id']) || !isset($input['comments'])) {
            http_response_code(400);
            echo json_encode(['error' => 'comments_id, user_id, and comments are required']);
            return;
        }

        $result = $this->comments->updatecomments($input['comments_id'], $input['user_id'], $input['comments']);
        
        if ($result['success']) {
            $comments_data = $this->comments->getcomments($input['comments_id']);
            
            echo json_encode([
                'success' => true,
                'message' => $result['message'],
                'comment' => $comment_data
            ]);
        } else {
            http_response_code(400);
            echo json_encode($result);
        }
    }

    private function handleDelete() {
        parse_str(file_get_contents('php://input'), $input);
        
        if (!isset($input['comment_id']) || !isset($input['user_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'comment_id and user_id are required']);
            return;
        }

        $result = $this->comment->deletecomment($input['comment_id'], $input['user_id']);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => $result['message']
            ]);
        } else {
            http_response_code(400);
            echo json_encode($result);
        }
    }

    private function handleGet() {
        $action = $_GET['action'] ?? 'get_by_post';
        
        switch ($action) {
            case 'get_by_post':
                $post_id = $_GET['post_id'] ?? null;
                if (!$post_id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'post_id is required']);
                    return;
                }
                
                $limit = $_GET['limit'] ?? 10;
                $offset = $_GET['offset'] ?? 0;
                $order = $_GET['order'] ?? 'ASC';
                
                $comment = $this->comment->getcommentByPost($post_id, $limit, $offset, $order);
                $total_comment = $this->comment->getcommentCount($post_id);
                
                echo json_encode([
                    'comment' => $comment,
                    'total_comment' => $total_comment
                ]);
                break;
                
            case 'get_by_user':
                $user_id = $_GET['user_id'] ?? null;
                if (!$user_id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'user_id is required']);
                    return;
                }
                
                $comment = $this->comment->getcommentByUser($user_id);
                echo json_encode(['comment' => $comment]);
                break;
                
            case 'search':
                $keyword = $_GET['keyword'] ?? null;
                if (!$keyword) {
                    http_response_code(400);
                    echo json_encode(['error' => 'keyword is required']);
                    return;
                }
                
                $comment = $this->comment->searchcomment($keyword);
                echo json_encode(['comment' => $comment]);
                break;
                
            case 'recent':
                $limit = $_GET['limit'] ?? 10;
                $comment = $this->comment->getRecentcomment($limit);
                echo json_encode(['comment' => $comment]);
                break;
                
            case 'analytics':
                $days = $_GET['days'] ?? 30;
                $analytics = $this->comment->getcommentAnalytics($days);
                echo json_encode(['analytics' => $analytics]);
                break;
                
            default:
                http_response_code(400);
                echo json_encode(['error' => 'Invalid action']);
        }
    }
}

// 4. UTILITY FUNCTIONS
class commentUtils {
    /**
     * Format comment text (basic sanitization and formatting)
     */
    public static function formatcomment($comment) {
        // Basic sanitization
        $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
        
        // Convert URLs to links
        $comment = preg_replace(
            '/(https?:\/\/[^\s]+)/',
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
            $comment
        );
        
        // Convert line breaks
        $comment = nl2br($comment);
        
        return $comment;
    }

    /**
     * Get time ago format
     */
    public static function timeAgo($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 2592000) return floor($time/86400) . ' days ago';
        
        return date('M j, Y', strtotime($datetime));
    }

    /**
     * Truncate comment text
     */
    public static function truncatecomment($comment, $length = 100) {
        if (strlen($comment) <= $length) {
            return $comment;
        }
        
        return substr($comment, 0, $length) . '...';
    }
}

// 5. USAGE EXAMPLES

// Initialize
$database = new Database();
$comment = new comment($database);

// Example: Add a comment
$result = $comment->addcomment(1, 123, "This is a great post!");
if ($result['success']) {
    echo "comment added successfully! ID: " . $result['comment_id'];
}

// Example: Get comment for a post
$post_comment = $comment->getcommentByPost(1, 5);
foreach ($post_comment as $comment) {
    echo $comment['username'] . ": " . $comment['coment'] . "\n";
}

// Example: Update a comment
$result = $comment->updatecomment(1, 123, "Updated comment text");
echo $result['message'];

// Example: Get comment count
$count = $comment->getcommentCount(1);
echo "Total comment: " . $count;

// For API usage, uncomment below:
// $api = new commentAPI($comment);
// $api->handleRequest();

?>