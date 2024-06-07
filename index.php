<?php
session_start(); 
    function connectDatabase() {
        try {
            $conexao = new PDO("mysql:host=127.0.0.1:8889;dbname=armalite_db", "root", "root");
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexao;
        } catch (PDOException $e) {
            echo "<p style='color:red;'>Erro ao conectar ao banco de dados: " . $e->getMessage() . "</p>";
            exit();
        }
    }

    function getUser($conexao, $id) {
        $query = $conexao->prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL");
        $query->execute(['id' => $id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    function handlePostRequest($conexao) {
        if (!empty($_POST['name']) && !empty($_POST['email'])) {
            $nome = filter_var(trim($_POST['name']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if (isset($_POST['id'])) {
                    updateUser($conexao, $_POST['id'], $nome, $email);
                } else {
                    insertUser($conexao, $nome, $email);
                }
                $_SESSION['success_message'] = "Dados recebidos com sucesso! Nome: $nome, E-mail: $email";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<p style='color:red;'>Por favor, insira um e-mail válido.</p>";
            }
        } else {
            echo "<p style='color:red;'>Por favor, preencha todos os campos do formulário.</p>";
        }
    }

    function updateUser($conexao, $id, $nome, $email) {
        $query = $conexao->prepare("UPDATE users SET name = :name, email = :email, updated_at = NOW() WHERE id = :id");
        $query->execute(['name' => $nome, 'email' => $email, 'id' => $id]);
        $_SESSION['success_message'] = "Usuário atualizado com sucesso! Nome: $nome, E-mail: $email";
    }

    function insertUser($conexao, $nome, $email) {
        $query = $conexao->prepare("INSERT INTO users (name, email, created_at, updated_at) VALUES (:name, :email, NOW(), NOW())");
        $query->execute(['name' => $nome, 'email' => $email]);
        $_SESSION['success_message'] ="Dados recebidos com sucesso! Nome: $nome, E-mail: $email";
        
    }

    function deleteUser($conexao, $id) {
        $query = $conexao->prepare("UPDATE users SET deleted_at = NOW() WHERE id = :id");
        $query->execute(['id' => $id]);
        $_SESSION['success_message'] ="Usuário excluído com sucesso!</p>";
    }

    function listUsers($conexao) {
        $query = $conexao->prepare("SELECT * FROM users WHERE deleted_at IS NULL");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    $conexao = connectDatabase();
    $formSuccess = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        handlePostRequest($conexao);
    }

    if (isset($_GET['delete'])) {
        deleteUser($conexao, $_GET['delete']);
    }

    $users = listUsers($conexao);
    $user = null;
    if (isset($_GET['edit'])) {
        $user = getUser($conexao, $_GET['edit']);
    }
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro</title>
    <script>
        function validateForm() {
            const isEditing = document.getElementById('editMode').value === 'true';
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!isEditing) {
                if (name.trim() === '') {
                    alert('Por favor, preencha o campo nome.');
                    return false;
                }

                if (!emailRegex.test(email)) {
                    alert('Por favor, insira um e-mail válido.');
                    return false;
                }
            }
            return true;
        }

        function clearForm() {
            document.getElementById('name').value = '';
            document.getElementById('email').value = '';
        }
    </script>
</head>

<body>
    <h2>Cadastro de Usuário</h2>
    <?php
    if (isset($_SESSION['success_message'])) {
        echo "<p style='color:green;'>" . $_SESSION['success_message'] . "</p>";
        unset($_SESSION['success_message']);
    }
    ?>
    <?php if ($user) : ?>
        <h2>Editar Usuário</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return validateForm();">
            <input type="hidden" id="editMode" name="editMode" value="true">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br><br>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>
            <input type="submit" value="Atualizar">
            <button type="button" onclick="window.location.href='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>'">Cancelar</button>
        </form>
    <?php else : ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return validateForm();">
            <input type="hidden" id="editMode" name="editMode" value="false">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" required><br><br>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required><br><br>
            <input type="submit" value="Enviar">
        </form>
    <?php endif; ?>

    <?php if (count($users) > 0) : ?>
        <h2>Lista de Usuários</h2>
        <table border='1'>
            <tr><th>ID</th><th>Nome</th><th>E-mail</th><th>Ações</th></tr>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <a href='?edit=<?php echo htmlspecialchars($user['id']); ?>'>Editar</a> | 
                        <a href='?delete=<?php echo htmlspecialchars($user['id']); ?>' onclick='return confirm("Tem certeza que deseja excluir este usuário?");'>Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>Nenhum usuário cadastrado.</p>
    <?php endif; ?>
</body>

</html>
