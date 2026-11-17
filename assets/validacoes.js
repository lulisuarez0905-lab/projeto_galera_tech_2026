const form = document.getElementById('formInscricao');
const nascimento = document.getElementById('nascimento');
const sucesso = document.getElementById('mensagemSucesso');
const apenasNumeros = valor => valor.replace(/\D/g, '');
const campo = id => document.getElementById(id);
function cpfValido(valor) {
    const cpf = apenasNumeros(valor);
    if (!/^\d{11}$/.test(cpf) || /^(\d)\1{10}$/.test(cpf)) return false;
    for (let pos = 9; pos <= 10; pos++) {
        let soma = 0;
        for (let i = 0; i < pos; i++) soma += Number(cpf[i]) * (pos + 1 - i);
        const digito = (soma * 10) % 11;
        if (Number(cpf[pos]) !== (digito === 10 ? 0 : digito)) return false;
    }
    return true;
}
function idadeEmAnos(data) {
    if (!data) return null;
    const hoje = new Date();
    const n = new Date(data + 'T12:00:00');
    if (Number.isNaN(n.getTime()) || n > hoje) return null;
    let idade = hoje.getFullYear() - n.getFullYear();
    if (hoje.getMonth() < n.getMonth() || (hoje.getMonth() === n.getMonth() && hoje.getDate() < n.getDate())) idade--;
    return idade;
}
function formatarTelefone(v) {
    const x = apenasNumeros(v).slice(0, 11);
    if (x.length <= 2) return x ? '(' + x : '';
    return '(' + x.slice(0, 2) + ') ' + (x.length > 10 ? x.slice(2, 7) : x.slice(2, 6)) + (x.length > 10 ? (x.length > 7 ? '-' + x.slice(7) : '') : (x.length > 6 ? '-' + x.slice(6) : ''));
}
function atualizarResponsavel() {
    const idade = idadeEmAnos(nascimento.value), obrigatorio = idade !== null && idade < 18;
    document.querySelectorAll('.responsavel').forEach(el => { el.required = obrigatorio; });
    campo('asteriscoResponsavel').textContent = obrigatorio ? '*' : '';
    campo('asteriscoTelefoneResponsavel').textContent = obrigatorio ? '*' : '';
    campo('asteriscoParentesco').textContent = obrigatorio ? '*' : '';
    campo('ajudaResponsavel').textContent = idade === null ? 'Informe a data de nascimento para verificar se os dados do responsável são obrigatórios.' : obrigatorio ? 'O estudante é menor de 18 anos: informe nome, WhatsApp e tipo de parentesco do responsável.' : 'O estudante tem 18 anos ou mais: nome, WhatsApp e parentesco do responsável são opcionais.';
}
// Máscaras didáticas: não substituem a validação feita no servidor PHP.
campo('cpf').addEventListener('input', e => { let x = apenasNumeros(e.target.value).slice(0, 11); e.target.value = x.replace(/^(\d{3})(\d)/, '$1.$2').replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3').replace(/\.(\d{3})(\d)/, '.$1-$2'); });
campo('cep').addEventListener('input', e => { let x = apenasNumeros(e.target.value).slice(0, 8); e.target.value = x.replace(/^(\d{5})(\d)/, '$1-$2'); });
['telefone', 'responsavelTelefone'].forEach(id => campo(id).addEventListener('input', e => e.target.value = formatarTelefone(e.target.value)));
nascimento.max = new Date().toISOString().slice(0, 10);
nascimento.addEventListener('change', atualizarResponsavel);
function validarCamposExtras() {
    const cpf = campo('cpf'), cep = campo('cep'), telefone = campo('telefone'), telResp = campo('responsavelTelefone');
    cpf.setCustomValidity(cpfValido(cpf.value) ? '' : 'CPF inválido');
    cep.setCustomValidity(apenasNumeros(cep.value).length === 8 ? '' : 'CEP inválido');
    telefone.setCustomValidity(apenasNumeros(telefone.value).length === 11 ? '' : 'Telefone inválido');
    telResp.setCustomValidity(!telResp.required && !telResp.value.trim() || apenasNumeros(telResp.value).length === 11 ? '' : 'Telefone inválido');
    const idade = idadeEmAnos(nascimento.value);
    nascimento.setCustomValidity(idade === null ? 'Data inválida' : '');
}
form.addEventListener('submit', e => {
    e.preventDefault(); sucesso.classList.add('d-none'); atualizarResponsavel(); validarCamposExtras(); form.classList.add('was-validated');
    if (!form.checkValidity()) { const primeiro = form.querySelector(':invalid'); primeiro?.focus(); return; }
    form.submit();
});
form.querySelectorAll('input,select').forEach(el => el.addEventListener('input', () => { el.setCustomValidity(''); sucesso.classList.add('d-none'); }));
form.addEventListener('reset', () => { form.classList.remove('was-validated'); sucesso.classList.add('d-none'); setTimeout(atualizarResponsavel, 0); });
atualizarResponsavel();


const cepInput = campo('cep');
const cepStatus = campo('cepStatus');
let ultimoCep = '';

async function buscarCep() {
    const cep = apenasNumeros(cepInput.value);

   
    if (cep.length < 8) {
        ultimoCep = '';
        cepStatus.textContent = '';
        return;
    }
    if (cep.length !== 8 || cep === ultimoCep) return;

    ultimoCep = cep;
    cepStatus.textContent = 'Buscando endereço...';

    try {
        const resp = await fetch('https://viacep.com.br/ws/' + cep + '/json/');
        if (!resp.ok) throw new Error('Falha na consulta');
        const d = await resp.json();

        if (d.erro) {
            cepStatus.textContent = 'CEP não encontrado. Preencha o endereço manualmente.';
            return;
        }

        campo('logradouro').value = d.logradouro || '';
        campo('bairro').value = d.bairro || '';
        campo('cidade').value = d.localidade || '';
        campo('estado').value = d.uf || '';


        ['logradouro', 'bairro', 'cidade', 'estado'].forEach(id =>
            campo(id).dispatchEvent(new Event('input', { bubbles: true }))
        );

        cepStatus.textContent = 'Endereço preenchido. Confira os dados.';

        (d.logradouro ? campo('numero') : campo('logradouro')).focus();
    } catch (err) {
        cepStatus.textContent = 'Não foi possível buscar o CEP agora. Preencha manualmente.';
    }
}
cepInput.addEventListener('input', buscarCep);

const etapas = [
    { id: 'secDados', nome: 'Dados pessoais' },
    { id: 'secEndereco', nome: 'Endereço' },
    { id: 'secEscola', nome: 'Escola' },
    { id: 'secResponsavel', nome: 'Responsável' },
    { id: 'secDeclaracoes', nome: 'Confirmação' }
];

function secaoCompleta(id) {
    const sec = campo(id);
    // a etapa do responsável só conta depois que a data de nascimento é informada
    if (id === 'secResponsavel' && idadeEmAnos(nascimento.value) === null) return false;
    return [...sec.querySelectorAll('input,select')].every(el => {
        if (!el.required) return true;
        return el.type === 'checkbox' ? el.checked : el.value.trim() !== '';
    });
}

function atualizarProgresso() {
    const status = etapas.map(et => secaoCompleta(et.id));
    const feitas = status.filter(Boolean).length;
    const atual = status.indexOf(false); // primeira etapa incompleta
    const pct = Math.round((feitas / etapas.length) * 100);

    campo('barraProgressoFill').style.width = pct + '%';
    campo('barraProgresso').setAttribute('aria-valuenow', pct);
    campo('etapaPercentual').textContent = pct + '%';
    campo('etapaTexto').textContent = atual === -1
        ? 'Tudo preenchido! Pode enviar sua inscrição.'
        : 'Etapa ' + (atual + 1) + ' de ' + etapas.length + ' · ' + etapas[atual].nome;

    document.querySelectorAll('.etapas-lista li').forEach((li, i) => {
        li.classList.toggle('completa', status[i]);
        li.classList.toggle('ativa', i === atual);
    });
}

document.querySelectorAll('.etapas-lista li').forEach(li =>
    li.addEventListener('click', () =>
        campo(li.dataset.alvo).scrollIntoView({ behavior: 'smooth', block: 'start' })
    )
);

form.addEventListener('input', atualizarProgresso);
form.addEventListener('change', atualizarProgresso);
form.addEventListener('reset', () => setTimeout(() => {
    ultimoCep = '';
    cepStatus.textContent = '';
    atualizarProgresso();
}, 0));
atualizarProgresso();