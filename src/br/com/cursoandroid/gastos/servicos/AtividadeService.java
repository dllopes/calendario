package br.com.cursoandroid.gastos.servicos;

import br.com.cursoandroid.gastos.dao.DaoFactory;
import br.com.cursoandroid.gastos.dao.IAtividadeDao;
import br.com.cursoandroid.gastos.dominio.Atividade;

public class AtividadeService {
	
	private static IAtividadeDao atividadeDao;
	private static AtividadeService singleton;
	
	static {
		AtividadeService.singleton = new AtividadeService();
	}
	
	public static AtividadeService getInstance(){
		return AtividadeService.singleton;
	}
	
	private AtividadeService() {
		AtividadeService.atividadeDao = DaoFactory.getAtividadeDao();
	}
	
	public Atividade save(Atividade obj){
		if(obj.getId() != null)
			return AtividadeService.atividadeDao.update(obj);
		
		return AtividadeService.atividadeDao.insert(obj);
	}

}
