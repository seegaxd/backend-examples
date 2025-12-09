import { Controller, Get } from '@nestjs/common';
import { AppService } from './app.service';
import { Roles, Unprotected } from 'nest-keycloak-connect'; 

@Controller()
export class AppController {
  constructor(private readonly appService: AppService) {}

  @Get()
  @Unprotected() 
  getHello(): string {
    return this.appService.getHello();
  }

  @Get('/view')
  @Roles({ roles: ['ProductsApiViewer'] }) 
  getView(): string {
    return 'Я вижу продукты!';
  }

  @Get('/edit')
  @Roles({ roles: ['ProductsApiWriter'] }) 
  getEdit(): string {
    return 'Я могу редактировать!';
  }
}