/**
 * Funções de Validação de Formulários
 */

// -----------------------------------------------------
// 1. VALIDAÇÃO DE LOGIN (index.php)
// -----------------------------------------------------
function validarFormularioLogin() {
    const email = document.querySelector('input[name=email]');
    const senha = document.querySelector('input[name=senha]');

    // trim() remove espaços em branco no início e fim
    if (!email.value.trim() || !senha.value) {
        alert('Preencha todos os campos!');
        return false;
    }

    return true; // Prossegue com o envio do formulário
}

// -----------------------------------------------------
// 2. VALIDAÇÃO DE CADASTRO (cadastro.php/register.php)
// -----------------------------------------------------
function validarFormularioCadastro() {
    const nome = document.querySelector('input[name=nome]');
    const email = document.querySelector('input[name=email]');
    const senha = document.querySelector('input[name=senha]');
    const confirmar = document.querySelector('input[name=confirmar]'); 

    if (!nome.value.trim() || !email.value.trim() || !senha.value || !confirmar.value) {
        alert('Preencha todos os campos!');
        return false;
    }

    if (senha.value.length < 6) {
        alert('A senha deve ter pelo menos 6 caracteres!');
        return false;
    }

    // CRUCIAL: Verificação de Confirmação de Senha
    if (senha.value !== confirmar.value) {
        alert('A senha e a confirmação de senha não coincidem!');
        return false;
    }

    return true; // Prossegue com o envio do formulário
}