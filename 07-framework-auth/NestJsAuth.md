# Лабораторна робота №5. Аутентифікація та авторизація NestJs

## Мета: Налагодження аутентифікації та авторизації за допомогою Keycloak

## Теоретичні відомості

**Автентифікація** – це підтвердження того, ким є користувач на вході. Це проходження перевірки автентичності.
**Авторизація** – це те, що користувачу дозволяється робити після входу.

**Keycloak** — це рішення з відкритим вихідним кодом для управління ідентифікацією та доступом, призначене для сучасних
програм і сервісів. Це полегшує захист додатків і служб за допомогою мінімального коду або без нього. Keycloak
використовує стандарти відкритого протоколу, такі як Open ID Connect або SAML 2.0, особливо в сценаріях Identity
Federation і SSO.

### Конфігурація додатку NestJs

Для зв’язку з сервером KeycloakМи використовуємо адаптер nest-keycloak-connect.

Keycloak Client Adapers — це бібліотеки, які спрощують захист програм і служб за допомогою Keycloak.

Додамо бібліотеку nest Keycloak до нашої кодової бази

```bash
npm install nest-keycloak-connect --save
```

Тепер нам потрібно додати конфігурацію Keycloak до NestJs.

'app.module.ts'

```typescript
import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import {
  KeycloakConnectModule,
  ResourceGuard,
  RoleGuard,
  AuthGuard,
} from 'nest-keycloak-connect';
import { APP_GUARD } from '@nestjs/core';

@Module({
  imports: [
    KeycloakConnectModule.register({
      authServerUrl: 'http://localhost:8080/auth',
      realm: 'Demo-Realm',
      clientId: 'nest-app',
      secret: '83790b4f-48cd-4b6c-ac60-451a918be4b9',
      // Secret key of the client taken from keycloak server
    }),
  ],
  controllers: [AppController],
  providers: [
    AppService,
    // This adds a global level authentication guard,
    // you can also have it scoped
    // if you like.
    //
    // Will return a 401 unauthorized when it is unable to
    // verify the JWT token or Bearer header is missing.
    {
      provide: APP_GUARD,
      useClass: AuthGuard,
    },
    // This adds a global level resource guard, which is permissive.
    // Only controllers annotated with @Resource and 
    // methods with @Scopes
    // are handled by this guard.
    {
      provide: APP_GUARD,
      useClass: ResourceGuard,
    },
    // New in 1.1.0
    // This adds a global level role guard, which is permissive.
    // Used by `@Roles` decorator with the 
    // optional `@AllowAnyRole` decorator for allowing any
    // specified role passed.
    {
      provide: APP_GUARD,
      useClass: RoleGuard,
    },
  ],
})
export class AppModule {}
```

Тут використовується AuthGuard, ResourceGuard, RoleGuard від nest-keycloak-connect для захисту наших кінцевих точок.
Тепер додаємо кінцеві точки до контролера та запускаємо додаток за допомогою npm start.

'app.controller.ts'

```typescript
@Controller()
export class UserController {
  constructor (private readonly userService: UserService) {}

  @Get()
  getpublic (): string {
    return `${this.userService.getHello()} from public`;
  }

  @Get('/user')
  getUser (): string {
    return `${this.userService.getHello()} from user`;
  }

  @Get('/admin')
  getAdmin (): string {
    return `${this.userService.getHello()} from admin`;
  }

  @Get('/all')
  getAll (): string {
    return `${this.userService.getHello()} from all`;
  }
}
```

Спробувавши отримати доступ до цих кінцевих точок - отримуємо

```json
  {
    "statusCode": 401,
    "message": "Unauthorized"
  } 
```

Щоб надати доступ до цих кінцевих точок, ми можемо використовувати наступні анотації.

'app.controller.ts'

```typescript
@Controller()
export class UserController {
  constructor (private readonly userService: UserService) {}

  @Get('/public')
  @Unprotected()
  getpublic (): string {
    return `${this.userService.getHello()} from public`;
  }

  @Get('/user')
  @Roles('user')
  getUser (): string {
    return `${this.userService.getHello()} from user`;
  }

  @Get('/admin')
  @Roles('admin')
  getAdmin (): string {
    return `${this.userService.getHello()} from admin`;
  }

  @Get('/all')
  @AllowAnyRole()
  getAll (): string {
    return `${this.userService.getHello()} from all`;
  }
}
```

Тепер ми можемо отримати доступ до кінцевої точки з JWT, приєднаним у заголовку авторизації.

[Оригінал статті](https://medium.com/devops-dudes/secure-nestjs-rest-api-with-keycloak-745ef32a2370)

## Завдана

1. Запустити сервіс Keycloak згідно вимог попередньої лабораторної роботи.
2. Створити клієнта `products-app` та ролі для перегляду та редагування даних у Keycloak:
   - `ProductsApiViewer`
   - `ProductsApiWriter`
3. Налаштувати проект NestJs для авторизації з використанням nest-keycloak-connect.
4. Для всіх ендпоінтів що відображають дані додати анотацію `@Roles('ProductsApiViewer')`, для всіх ендпоінтів що
   редагують дані додати анотацію `@Roles('ProductsApiWriter')`.
5. Додати `client_credentials` та `authorization_code` потоки авторизації до проєкту OpenAPI документації.
6. Протестувати роботу різних методів авторизації використовуючи Postman та OpenAPI клієнту.
7. Завантажити проєкт до власного репозиторію з назвою за шаблоном <vendor>/backend-labs-5 на GitHub/Bitbucket та надати
   посилання на нього у якості звіту.

## Контрольні питання

- Що таке Keycloak і які основні функції він надає?
- Які стандарти відкритого протоколу використовуються Keycloak для забезпечення автентифікації та авторизації?
- Як працює автентифікація з використанням Keycloak? Опишіть процес.
- Які переваги має використання токенів ідентифікації та стверджень в контексті застосування Keycloak?
- Які основні кроки потрібно виконати для налаштування сервера Keycloak?
- Що таке Realm в Keycloak і яка його роль?
- Які типи ролей можна визначити в Keycloak і яка їхня призначеність?
- Як створити клієнта в Keycloak і яку роль він відіграє у системі?
- Що таке Composite Roles в Keycloak і як вони працюють?
- Як створити користувача в Keycloak і як призначити йому ролі?
- Які дії потрібно виконати для конфігурації додатку NestJs для використання з Keycloak?

## Додаткові посилання

- https://medium.com/js-dojo/authentication-made-easy-in-vue-js-with-keycloak-c03c7fff67bb
- https://www.keycloak.org/getting-started/getting-started-docker
- https://wkrzywiec.medium.com/create-and-configure-keycloak-oauth-2-0-authorization-server-f75e2f6f6046
  https://github.com/robsontenorio/laravel-keycloak-guard
- https://medium.com/inspiredbrilliance/implementing-authentication-in-next-js-v13-application-with-keycloak-part-1-f4817c53c7ef
- https://medium.com/devops-dudes/secure-nestjs-rest-api-with-keycloak-745ef32a2370
- https://stackoverflow.com/questions/72968095/nestjs-with-swagger-ui-oauth-keycloak-cors-problem
- [Authentication made easy in Vue.js with Keycloak](https://medium.com/js-dojo/authentication-made-easy-in-vue-js-with-keycloak-c03c7fff67bb)
- [Getting started with Keycloak REST API](https://medium.com/@fairushyn/getting-started-with-keycloak-rest-api-c760ca398e3)
- [Доступ к конечным точкам Keycloak с помощью Postman](https://for-each.dev/lessons/b/-postman-keycloak-endpoints)
- [Keycloak Admin REST API](https://www.keycloak.org/docs-api/22.0.1/rest-api/index.html)
- [Secure NestJs Rest API with Keycloak](https://medium.com/devops-dudes/secure-nestjs-rest-api-with-keycloak-745ef32a2370)
- https://saurav-samantray.medium.com/dockerize-keycloak-21-with-a-custom-theme-b6f2acad03d5  / https://github.com/saurav-samantray/custom-auth-service
- https://github.com/baloise/vue-keycloak
- https://medium.com/devops-dudes/secure-nestjs-rest-api-with-keycloak-745ef32a2370
- [keycloak-slides](https://github.com/malys/keycloak-slides)
- [Цикл постов про Keycloak. Часть первая: Внедрение](https://habr.com/ru/articles/716232/)
