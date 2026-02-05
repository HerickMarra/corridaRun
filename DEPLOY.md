# Deploy Laravel no Render com Docker

Este projeto está configurado para deploy automático no Render usando Docker.

## 🚀 Deploy Rápido

### 1. Preparação

Certifique-se de que todas as alterações estão commitadas:

```bash
git add .
git commit -m "feat: add Docker configuration for Render"
git push origin main
```

### 2. Configurar no Render

1. Acesse [render.com](https://render.com) e faça login
2. Clique em "New +" → "Blueprint"
3. Conecte seu repositório GitHub
4. O Render detectará automaticamente o `render.yaml`
5. Clique em "Apply" para criar os serviços

### 3. Variáveis de Ambiente

O Render configurará automaticamente:
- ✅ `DATABASE_URL` - String de conexão PostgreSQL
- ✅ `APP_KEY` - Gerado automaticamente

**Você precisa adicionar manualmente:**
- `APP_URL` - URL do seu app (ex: https://seu-app.onrender.com)

### 4. Aguarde o Deploy

O Render irá:
1. Construir a imagem Docker
2. Criar o banco PostgreSQL
3. Executar as migrations automaticamente
4. Iniciar a aplicação

## 📦 Arquivos Docker

- **Dockerfile** - Imagem multi-stage otimizada
- **docker/nginx.conf** - Configuração Nginx
- **docker/default.conf** - Server block Laravel
- **docker/entrypoint.sh** - Script de inicialização
- **docker/supervisord.conf** - Gerenciamento de processos
- **render.yaml** - Configuração dos serviços Render

## 🔧 Recursos Configurados

- ✅ PHP 8.3 FPM + Nginx
- ✅ PostgreSQL (Render managed)
- ✅ Storage persistente (10GB)
- ✅ Migrations automáticas
- ✅ Cache de configuração
- ✅ Otimização para produção

## 🧪 Testar Localmente

```bash
# Build da imagem
docker build -t corridarun .

# Executar
docker run -p 8080:8080 \
  -e APP_KEY=base64:sua-chave-aqui \
  -e DATABASE_URL=postgres://user:pass@host:5432/db \
  corridarun
```

## 📝 Notas Importantes

1. **Storage**: Arquivos em `/storage/app` são persistidos no disco Render
2. **Logs**: Acessíveis via dashboard do Render
3. **Migrations**: Executam automaticamente a cada deploy
4. **Cache**: Configurações são cacheadas para melhor performance

## 🆘 Troubleshooting

### Erro de conexão com banco
- Verifique se o serviço PostgreSQL está rodando
- Confirme que `DATABASE_URL` está configurado

### Erro 500
- Verifique os logs no dashboard Render
- Confirme que `APP_KEY` está configurado
- Verifique permissões de storage

### Assets não carregam
- Confirme que `npm run build` executou com sucesso
- Verifique `APP_URL` nas variáveis de ambiente
