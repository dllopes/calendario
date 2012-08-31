package br.com.cursoandroid.gastos.database.dao;

public class DaoFactory {
	public static IAtividadeDao getAtividadeDao(){
		return new AtividadeDao();
	}
}
