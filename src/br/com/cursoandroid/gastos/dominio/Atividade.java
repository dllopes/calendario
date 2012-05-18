package br.com.cursoandroid.gastos.dominio;

import java.util.Date;


public class Atividade {
	public static final String RECEITA = "R";
	public static final String DESPESA = "D";
	
	private Integer id = null;
	private String nome;
	private Date dataInicio;
	private Float valor;
	private Atividade parent;
	private String tipo;
	
	public String getTipo() {
		return tipo;
	}
	public void setTipo(String tipo) {
		this.tipo = tipo;
	}
	public Integer getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public String getNome() {
		return nome;
	}
	public void setNome(String nome) {
		this.nome = nome;
	}
	public Date getDataInicio() {
		return dataInicio;
	}
	public void setDataInicio(Date dataInicio) {
		this.dataInicio = dataInicio;
	}
	public Float getValor() {
		return valor;
	}
	public void setValor(Float valor) {
		this.valor = valor;
	}
	public Atividade getParent() {
		return parent;
	}
	public void setParent(Atividade parent) {
		this.parent = parent;
	}	
	
	@Override
	public String toString() {
		// TODO Auto-generated method stub
		return this.nome + "   R$ " + this.valor.toString();
	}
}
