function revokeAccess(id) {
    if (confirm('Tem certeza que deseja revogar o acesso?')) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../models/revogar_acesso.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("id=" + id);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert('Acesso revogado com sucesso!');
                location.reload(); // Recarrega a página para atualizar a tabela
            }
        };
    }
}

function deleteUser(id) {
    if (confirm('Tem certeza que deseja deletar o usuário?')) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../models/deletar_usuario.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("id=" + id);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert('Usuário deletado com sucesso!');
                location.reload(); // Recarrega a página para atualizar a tabela
            }
        };
    }
}

function reAddAccess(id) {
    if (confirm('Tem certeza que deseja liberar o acesso novamente?')) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../models/liberar_novamente.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("id=" + id);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert('Acesso liberado novamente!');
                location.reload(); // Recarrega a página para atualizar a tabela
            }
        };
    }
}