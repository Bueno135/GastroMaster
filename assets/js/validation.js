/**
 * Validações JavaScript
 * GastroMaster - Sistema de Gerenciamento de Receitas
 */

// Validação do formulário de registro
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const senha = document.getElementById('senha').value;
            const confirmarSenha = document.getElementById('confirmar_senha').value;
            
            if (senha !== confirmarSenha) {
                e.preventDefault();
                alert('As senhas não coincidem!');
                return false;
            }
            
            if (senha.length < 6) {
                e.preventDefault();
                alert('A senha deve ter no mínimo 6 caracteres!');
                return false;
            }
        });
    }
    
    // Validação do formulário de receitas
    const receitaForm = document.getElementById('receitaForm');
    if (receitaForm) {
        receitaForm.addEventListener('submit', function(e) {
            const nome = document.getElementById('nome').value.trim();
            const categoria = document.getElementById('categoria').value;
            const ingredientes = document.getElementById('ingredientes').value.trim();
            const modoPreparo = document.getElementById('modo_preparo').value.trim();
            const tempoPreparo = document.getElementById('tempo_preparo').value.trim();
            const nivelDificuldade = document.getElementById('nivel_dificuldade').value;
            
            if (!nome || !categoria || !ingredientes || !modoPreparo || !tempoPreparo || !nivelDificuldade) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios!');
                return false;
            }
            
            // Validação de tamanho de arquivo
            const imagemInput = document.getElementById('imagem');
            if (imagemInput && imagemInput.files.length > 0) {
                const file = imagemInput.files[0];
                const maxSize = 5 * 1024 * 1024; // 5MB
                
                if (file.size > maxSize) {
                    e.preventDefault();
                    alert('O arquivo de imagem é muito grande. Tamanho máximo: 5MB.');
                    return false;
                }
                
                // Validação de tipo de arquivo
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    e.preventDefault();
                    alert('Tipo de arquivo não permitido. Use JPG, PNG ou GIF.');
                    return false;
                }
            }
        });
    }
    
    // Validação do formulário de login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const senha = document.getElementById('senha').value;
            
            if (!email || !senha) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos!');
                return false;
            }
            
            // Validação básica de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Por favor, insira um email válido!');
                return false;
            }
        });
    }
});

// Função para confirmar exclusão
function confirmDelete(message) {
    return confirm(message || 'Tem certeza que deseja excluir este item?');
}

