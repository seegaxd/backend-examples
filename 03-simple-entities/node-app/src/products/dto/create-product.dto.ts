import { IsString, IsNotEmpty, IsNumber, IsUUID } from 'class-validator';

export class CreateProductDto {
  @IsString()
  @IsNotEmpty()
  name: string;

  @IsString()
  @IsNotEmpty()
  description: string;

  @IsNumber()
  price: number;

  @IsString()
  @IsNotEmpty()
  image: string;

  @IsUUID()
  @IsNotEmpty()
  category_id: string;
}