package br.com.cursoandroid.gastos.dao;

public class DaoFactory {
	public static IAtividadeDao getAtividadeDao(){
		return new AtividadeDao();
	}
}
