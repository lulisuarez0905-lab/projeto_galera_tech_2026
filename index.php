<?php require_once __DIR__ . "/config.php"; ?>
<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Galera Tech | Inscrição</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <style>
/* BARRA DE PROGRESSO / ETAPAS */

:root {
    --etapas-fundo: rgba(58, 42, 122, 0.78);   /* lilás, pouco transparente */
    --etapas-borda: rgba(190, 170, 255, 0.35);
    --ouro-1: #C9A34A;
    --ouro-2: #F5D36B;
}

.etapas {
    background: var(--etapas-fundo);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid var(--etapas-borda);
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.35);
    padding: 1.1rem 1.35rem 1rem;
    margin-bottom: 1.2rem;
    color: var(--branco, #F0F4F8);
    position: sticky;
    top: .5rem;
    z-index: 10;
}

.etapas-topo {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: .65rem;
}

.etapas-titulo {
    font-weight: 600;
    font-size: .95rem;
    color: var(--branco, #F0F4F8);
}

.etapas-pct {
    font-weight: 700;
    font-size: .95rem;
    color: var(--ouro-2);
}

/* A barra em si */
.barra-etapas {
    width: 100%;
    height: 14px;
    background: rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 999px;
    overflow: hidden;
}

.barra-etapas-fill {
    height: 100%;
    width: 0;
    border-radius: 999px;
    background: linear-gradient(90deg, var(--ouro-1), var(--ouro-2));
    box-shadow: 0 0 10px rgba(245, 211, 107, 0.55);
    transition: width .45s ease;
}

.etapas-lista {
    list-style: none;
    display: flex;
    justify-content: space-between;
    gap: .5rem;
    margin: .9rem 0 0;
    padding: 0;
}

.etapas-lista li {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    text-align: center;
    cursor: pointer;
    font-size: .78rem;
    color: rgba(240, 244, 248, 0.6);
    transition: color .3s ease;
}

.etapas-lista .bolinha {
    display: grid;
    place-items: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.35);
    background: rgba(0, 0, 0, 0.2);
    font-weight: 700;
    font-size: .85rem;
    margin-bottom: .3rem;
    transition: all .3s ease;
}

.etapas-lista li.ativa { color: var(--branco, #F0F4F8); }
.etapas-lista li.ativa .bolinha {
    border-color: var(--ouro-2);
    color: var(--ouro-2);
    box-shadow: 0 0 0 3px rgba(245, 211, 107, 0.2);
}

.etapas-lista li.completa { color: var(--ouro-2); }
.etapas-lista li.completa .bolinha {
    background: linear-gradient(135deg, var(--ouro-1), var(--ouro-2));
    border-color: var(--ouro-2);
    color: #2a1f05;
}

@media (max-width: 576px) {
    .etapas { padding: .9rem 1rem; }
    .etapas-lista .rotulo { display: none; }
}

  </style>
</head>

<body>
  <header class="cabecalho">
    <div class="container" style="max-width:950px">
      <img src="assets/img/logo.png" alt="logo" class="logo">
      <div class="small text-uppercase fw-bold opacity-75 mb-2">Programa de estudos • Inscrição</div>
      <h1 class="h2 fw-bold mb-2">Galera Tech — Formulário de Inscrição</h1>
      <p class="mb-0 opacity-75">Preencha seus dados para realizar sua inscrição.</p>
    </div>
  </header>
  <main class="container py-4" style="max-width:950px">
    <div class="alert alert-info border-0" role="note"><strong>Ambiente didático:</strong> este formulário registra inscrições no banco MySQL local. Utilize <strong>somente dados fictícios</strong> nos testes.</div>
    <div class="alert alert-light border" role="note"><strong>Sobre o Galera Tech:</strong> capacitação gratuita em tecnologia, com aulas presenciais à tarde, <strong>exclusiva para estudantes que estejam cursando o Ensino Médio em escolas públicas</strong>. Antes de se inscrever, leia as condições do programa e confirme sua ciência na seção final.</div>
    <div id="mensagemSucesso" class="alert alert-success d-none" role="status" tabindex="-1"></div>

    <!-- BARRA DE PROGRESSO / ETAPAS -->
    <nav class="etapas" aria-label="Progresso da inscrição">
      <div class="etapas-topo">
        <span class="etapas-titulo" id="etapaTexto">Etapa 1 de 5</span>
        <span class="etapas-pct" id="etapaPercentual">0%</span>
      </div>
      <div class="barra-etapas" id="barraProgresso" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" aria-label="Progresso do preenchimento">
        <div class="barra-etapas-fill" id="barraProgressoFill"></div>
      </div>
      <ol class="etapas-lista">
        <li data-alvo="secDados"><span class="bolinha">1</span><span class="rotulo">Dados pessoais</span></li>
        <li data-alvo="secEndereco"><span class="bolinha">2</span><span class="rotulo">Endereço</span></li>
        <li data-alvo="secEscola"><span class="bolinha">3</span><span class="rotulo">Escola</span></li>
        <li data-alvo="secResponsavel"><span class="bolinha">4</span><span class="rotulo">Responsável</span></li>
        <li data-alvo="secDeclaracoes"><span class="bolinha">5</span><span class="rotulo">Confirmação</span></li>
      </ol>
    </nav>

    <form id="formInscricao" method="post" action="salvar_inscricao.php" novalidate><input type="hidden" name="csrf" value="<?= e(token()) ?>">
      <section class="card secao" id="secDados">
        <div class="card-header">
          <h2 class="h5 mb-0"><span class="numero">1</span>Dados pessoais do estudante</h2>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-8"><label for="nome" class="form-label">Nome completo <span class="obrigatorio">*</span></label><input class="form-control" id="nome" name="nome" autocomplete="name" maxlength="150" required>
              <div class="invalid-feedback">Informe o nome completo.</div>
            </div>
            <div class="col-md-4"><label for="nascimento" class="form-label">Data de nascimento <span class="obrigatorio">*</span></label><input type="date" class="form-control" id="nascimento" name="data_nascimento" required>
              <div class="invalid-feedback">Informe uma data de nascimento válida.</div>
            </div>
            <div class="col-md-6"><label for="cpf" class="form-label">CPF <span class="obrigatorio">*</span></label><input class="form-control" id="cpf" name="cpf" inputmode="numeric" placeholder="000.000.000-00" maxlength="14" required>
              <div class="invalid-feedback">Informe um CPF válido.</div>
            </div>
            <div class="col-md-6"><label for="rg" class="form-label">RG / Documento de identidade <span class="obrigatorio">*</span></label><input class="form-control" id="rg" name="rg" maxlength="30" required>
              <div class="invalid-feedback">Informe o documento de identidade.</div>
            </div>
            <div class="col-md-4"><label for="sexo" class="form-label">Sexo <span class="obrigatorio">*</span></label><select class="form-select" id="sexo" name="sexo" required>
                <option value="">Selecione</option>
                <option>Masculino</option>
                <option>Feminino</option>
                <option>Não desejo informar</option>
              </select>
              <div class="invalid-feedback">Selecione uma opção.</div>
            </div>
            <div class="col-md-4"><label for="email" class="form-label">E-mail <span class="obrigatorio">*</span></label><input type="email" class="form-control" id="email" name="email" maxlength="150" autocomplete="email" required>
              <div class="invalid-feedback">Informe um e-mail válido.</div>
            </div>
            <div class="col-md-4"><label for="telefone" class="form-label">WhatsApp do estudante (com DDD) <span class="obrigatorio">*</span></label><input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(17) 99999-9999" maxlength="15" inputmode="numeric" required>
              <div class="invalid-feedback">Informe um celular com DDD (11 dígitos).</div>
            </div>
          </div>
        </div>
      </section>
      <section class="card secao" id="secEndereco">
        <div class="card-header">
          <h2 class="h5 mb-0"><span class="numero">2</span>Residência e endereço</h2>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-12"><label for="resideRioPreto" class="form-label">Você reside em São José do Rio Preto? <span class="obrigatorio">*</span></label><select id="resideRioPreto" name="reside_rio_preto" class="form-select" required>
                <option value="">Selecione</option>
                <option value="sim">Sim</option>
                <option value="nao">Não</option>
              </select>
              <div class="invalid-feedback">Informe se reside em São José do Rio Preto.</div>
            </div>
            <div class="col-md-3"><label for="cep" class="form-label">CEP <span class="obrigatorio">*</span></label><input class="form-control" id="cep" name="cep" placeholder="00000-000" maxlength="9" inputmode="numeric" required>
              <div class="invalid-feedback">Informe um CEP com 8 dígitos.</div>
              <div class="form-text" id="cepStatus"></div>
            </div>
            <div class="col-md-7"><label for="logradouro" class="form-label">Logradouro <span class="obrigatorio">*</span></label><input class="form-control" id="logradouro" name="logradouro" maxlength="150" placeholder="Rua, avenida..." required>
              <div class="invalid-feedback">Informe o logradouro.</div>
            </div>
            <div class="col-md-2"><label for="numero" class="form-label">Número <span class="obrigatorio">*</span></label><input class="form-control" id="numero" name="numero" maxlength="15" required>
              <div class="invalid-feedback">Informe o número ou S/N.</div>
            </div>
            <div class="col-md-5"><label for="complemento" class="form-label">Complemento</label><input class="form-control" id="complemento" name="complemento" maxlength="100"></div>
            <div class="col-md-4"><label for="bairro" class="form-label">Bairro <span class="obrigatorio">*</span></label><input class="form-control" id="bairro" name="bairro" maxlength="100" required>
              <div class="invalid-feedback">Informe o bairro.</div>
            </div>
            <div class="col-md-5"><label for="cidade" class="form-label">Cidade <span class="obrigatorio">*</span></label><input class="form-control" id="cidade" name="cidade" maxlength="100" required>
              <div class="invalid-feedback">Informe a cidade.</div>
            </div>
            <div class="col-md-3"><label for="estado" class="form-label">Estado (UF) <span class="obrigatorio">*</span></label><select class="form-select" id="estado" name="estado" required>
                <option value="">Selecione</option>
                <option value="AC">Acre (AC)</option>
                <option value="AL">Alagoas (AL)</option>
                <option value="AP">Amapá (AP)</option>
                <option value="AM">Amazonas (AM)</option>
                <option value="BA">Bahia (BA)</option>
                <option value="CE">Ceará (CE)</option>
                <option value="DF">Distrito Federal (DF)</option>
                <option value="ES">Espírito Santo (ES)</option>
                <option value="GO">Goiás (GO)</option>
                <option value="MA">Maranhão (MA)</option>
                <option value="MT">Mato Grosso (MT)</option>
                <option value="MS">Mato Grosso do Sul (MS)</option>
                <option value="MG">Minas Gerais (MG)</option>
                <option value="PA">Pará (PA)</option>
                <option value="PB">Paraíba (PB)</option>
                <option value="PR">Paraná (PR)</option>
                <option value="PE">Pernambuco (PE)</option>
                <option value="PI">Piauí (PI)</option>
                <option value="RJ">Rio de Janeiro (RJ)</option>
                <option value="RN">Rio Grande do Norte (RN)</option>
                <option value="RS">Rio Grande do Sul (RS)</option>
                <option value="RO">Rondônia (RO)</option>
                <option value="RR">Roraima (RR)</option>
                <option value="SC">Santa Catarina (SC)</option>
                <option value="SP">São Paulo (SP)</option>
                <option value="SE">Sergipe (SE)</option>
                <option value="TO">Tocantins (TO)</option>
              </select>
              <div class="invalid-feedback">Selecione o estado.</div>
            </div>
          </div>
        </div>
      </section>
      <section class="card secao" id="secEscola">
        <div class="card-header">
          <h2 class="h5 mb-0"><span class="numero">3</span>Informações escolares</h2>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-8"><label for="escola" class="form-label">Nome completo da escola de origem <span class="obrigatorio">*</span></label><input type="text" class="form-control" id="escola" name="nome_escola" maxlength="150" placeholder="Digite o nome da escola" required>
              <div class="invalid-feedback">Informe o nome da escola.</div>
            </div>
            <div class="col-md-6"><label for="serie" class="form-label">Série / ano escolar <span class="obrigatorio">*</span></label><input type="text" class="form-control" id="serie" name="serie_escolar" placeholder="Ex.: 2º ano do Ensino Médio" maxlength="80" required>
              <div class="invalid-feedback">Informe a série ou ano escolar.</div>
            </div>
            <div class="col-md-6"><label for="origemDivulgacao" class="form-label">Por onde conheceu o Galera Tech? <span class="obrigatorio">*</span></label><select class="form-select" id="origemDivulgacao" name="origem_divulgacao" required>
                <option value="">Selecione</option>
                <option>Instagram / Redes sociais</option>
                <option>Escola (Diretoria / Professores)</option>
                <option>Amigos ou familiares</option>
                <option>Anúncio no rádio ou TV</option>
                <option>Outros</option>
              </select>
              <div class="invalid-feedback">Selecione como conheceu o projeto.</div>
            </div>
          </div>
          <div class="alert alert-primary mt-3 mb-0" role="note" id="informacaoTransporte"><strong>Transporte e deslocamento:</strong> o auxílio-transporte (cartão Circular Santa Luzia), quando disponibilizado pelo programa, é destinado aos estudantes matriculados em escolas do município de São José do Rio Preto. Para residentes de outras cidades, os custos e a logística de deslocamento às atividades presenciais ficam sob responsabilidade do candidato ou responsável.</div>
          <p class="small text-secondary mt-3 mb-0">O Galera Tech ocorre à tarde. O ano e o semestre da inscrição serão definidos pelo sistema, não pelo candidato.</p>
        </div>
      </section>
      <section class="card secao" id="secResponsavel">
        <div class="card-header">
          <h2 class="h5 mb-0"><span class="numero">4</span>Dados do responsável</h2>
        </div>
        <div class="card-body">
          <p class="small text-secondary" id="ajudaResponsavel">Nome, WhatsApp e parentesco são obrigatórios para estudantes menores de 18 anos e opcionais para maiores.</p>
          <div class="row g-3">
            <div class="col-md-7"><label for="responsavelNome" class="form-label">Nome completo do responsável <span class="obrigatorio" id="asteriscoResponsavel">*</span></label><input class="form-control responsavel" id="responsavelNome" name="responsavel_nome" maxlength="150" autocomplete="off">
              <div class="invalid-feedback">Informe o nome do responsável.</div>
            </div>
            <div class="col-md-5"><label for="parentesco" class="form-label">Grau de parentesco com o estudante <span class="obrigatorio" id="asteriscoParentesco">*</span></label><select class="form-select responsavel" id="parentesco" name="parentesco">
                <option value="">Selecione o grau de parentesco</option>
                <option value="Mãe">Mãe</option>
                <option value="Pai">Pai</option>
                <option value="Tutor(a) / Responsável legal">Tutor(a) / Responsável legal</option>
                <option value="Outro">Outro</option>
              </select>
              <div class="invalid-feedback">Selecione o parentesco.</div>
            </div>
            <div class="col-md-5"><label for="responsavelTelefone" class="form-label">WhatsApp do responsável (com DDD) <span class="obrigatorio" id="asteriscoTelefoneResponsavel">*</span></label><input type="tel" class="form-control responsavel" id="responsavelTelefone" name="responsavel_telefone" maxlength="15" inputmode="numeric" placeholder="(17) 99999-9999">
              <div class="invalid-feedback">Informe um WhatsApp com DDD (11 dígitos).</div>
            </div>
          </div>
        </div>
      </section>
      <section class="card secao" id="secDeclaracoes">
        <div class="card-header">
          <h2 class="h5 mb-0"><span class="numero">5</span>Declarações e confirmação</h2>
        </div>
        <div class="card-body">
          <div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="cienciaPrograma" name="ciencia_programa" required><label class="form-check-label" for="cienciaPrograma"><strong>Li e compreendi as condições do Galera Tech:</strong> a capacitação é gratuita, presencial no período da tarde e <strong>exclusiva para estudantes do Ensino Médio de escolas públicas</strong>. <span class="obrigatorio">*</span></label>
            <div class="invalid-feedback">Confirme que leu e compreendeu as condições de participação.</div>
          </div>
          <div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="cienciaTransporte" name="ciencia_transporte" required><label class="form-check-label" for="cienciaTransporte"><strong>Li e compreendi as condições de transporte e deslocamento</strong> descritas acima, incluindo as regras do auxílio-transporte e a responsabilidade pelos custos e pela logística de deslocamento quando aplicável. <span class="obrigatorio">*</span></label>
            <div class="invalid-feedback">Confirme a ciência das condições de transporte e deslocamento.</div>
          </div>
          <div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="veracidade" name="declarou_veracidade" required><label class="form-check-label" for="veracidade">Declaro que as informações fornecidas são verdadeiras. <span class="obrigatorio">*</span></label>
            <div class="invalid-feedback">É necessário confirmar esta declaração.</div>
          </div>
          <div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="privacidade" name="ciencia_privacidade" required><label class="form-check-label" for="privacidade">Declaro que li e estou ciente do <a href="aviso-privacidade.html" target="_blank" rel="noopener noreferrer">Aviso de Privacidade e Tratamento de Dados (abrir documento)</a> referente à inscrição. <span class="obrigatorio">*</span></label>
            <div class="invalid-feedback">É necessário registrar a ciência do aviso de privacidade.</div>
          </div>
          <p class="small text-secondary">* Campos obrigatórios. O aviso de privacidade está disponível no link acima; o texto é uma minuta que deverá ser revisada pela organização antes de receber dados reais.</p>
          <div class="d-flex gap-2 flex-wrap"><button type="submit" class="btn btn-primary px-4">Enviar inscrição</button><button type="reset" class="btn btn-outline-secondary">Limpar formulário</button></div>
        </div>
      </section>
    </form>
    <footer class="text-center py-3">Galera Tech • PHP, MySQL e Bootstrap</footer>
  </main>
  <script src="assets/validacoes.js"></script>
</body>

</html>