# 🏃‍♂️ Corrida Final - Plataforma de Gestão de Eventos Esportivos

Este projeto é uma plataforma completa para gestão de inscrições em corridas e eventos esportivos, com integração direta de pagamentos (Asaas) e ferramentas de marketing.

---

## 🛠️ Pré-requisitos
Antes de começar, você precisará ter instalado em sua máquina:
- **PHP 8.2+**
- **Composer** (Gerenciador de dependências PHP)
- **Node.js & NPM** (Para assets e frontend)
- **SQLite** ou **MySQL** (O projeto vem configurado para SQLite por padrão para facilitar o dev local)

---

## 💻 Instalação Local (Desenvolvimento)

Siga os passos abaixo para rodar o projeto em seu ambiente local:

1. **Clonar o repositório:**
   ```bash
   git clone <url-do-repositorio>
   cd corridafinal
   ```

2. **Instalar dependências do PHP:**
   ```bash
   composer install
   ```

3. **Instalar dependências do Frontend:**
   ```bash
   npm install
   ```

4. **Configurar o ambiente:**
   Copie o arquivo de exemplo e gere a chave da aplicação:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurar o Banco de Dados:**
   Por padrão, o projeto usa SQLite. Crie o arquivo do banco caso não exista:
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

6. **Compilar os assets:**
   ```bash
   npm run dev
   ```

---

## 🚀 Operação do Sistema (Comandos Essenciais)

Para o sistema funcionar 100% (pagamentos e e-mails agendados), você precisa rodar **três processos** simultaneamente no terminal:

### 1. Servidor Web
Roda a interface do site:
```bash
php artisan serve
```

### 2. Processador de Filas (Queues)
Necessário para enviar e-mails de confirmação e processar campanhas de marketing em segundo plano:
```bash
php artisan queue:work
```

### 3. Agendador de Tarefas (Scheduler)
Necessário para disparar os e-mails de marketing agendados:
- **Local:** `php artisan schedule:work`
- **Produção:** Configurar Cron no servidor (veja abaixo).

---

## 🌐 Configuração de Produção

### 🐳 Docker (Recomendado)
O projeto já possui um `Dockerfile` e arquivo de configuração pronto para deploy.
Para rodar via Docker:
```bash
docker build -t corridafinal .
docker run -p 8000:80 corridafinal
```

### ⏰ Configurando o Cron (Scheduler)
No seu servidor Linux, adicione a seguinte linha ao `crontab -e`:
```bash
* * * * * cd /caminho-da-sua-aplicacao && php artisan schedule:run >> /dev/null 2>&1
```

### 📧 Variáveis de Ambiente (.env)
Certifique-se de configurar as seguintes chaves em produção:
- `ASAAS_KEY`: Sua chave de API do Asaas.
- `ASAAS_URL`: `https://api.asaas.com/v3` (Produção) ou `https://sandbox.asaas.com/v3` (Teste).
- `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`: Para envio de e-mails.

---

## 🛡️ Webhooks do Asaas
Para que o sistema receba confirmações de pagamento automáticas:
1. Cadastre no painel do Asaas a URL: `https://seu-dominio.com/webhook/asaas`
2. Configure o Token de Webhook gerado no seu `.env` sob a chave `ASAAS_WEBHOOK_TOKEN`.

---

## ✨ Principais Funcionalidades
- **Checkout Integrado:** Pix, Boleto e Cartão com verificação em tempo real.
- **Painel do Atleta:** Histórico de inscrições e download de comprovantes com QR Code.
- **Painel Admin:** Gestão de corridas, categorias, kits e cupons.
- **Mail Marketing:** Disparo de campanhas segmentadas por evento e newsletter.
- **Kanban de Gestão:** Acompanhamento de tarefas por corrida.
