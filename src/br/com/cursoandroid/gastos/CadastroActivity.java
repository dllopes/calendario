package br.com.cursoandroid.gastos;

import br.com.cursoandroid.gastos.dominio.Atividade;
import android.app.Activity;
import android.content.Context;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

public class CadastroActivity extends Activity {
	private Context self = this;
	private OnClickListener btnCadastrarClickListener = new Button.OnClickListener() {
		@Override
		public void onClick(View view) {
			Toast.makeText(self, "clicou", Toast.LENGTH_LONG).show();
		}
	}; 
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.cadastro_atividade);
		
		Bundle extras = getIntent().getExtras();
		String tipocadastro = extras.getString("tipocadastro");
		TextView tituloCadastro = (TextView) findViewById(R.id.labelForm);
		
		if(tipocadastro.equals(Atividade.DESPESA))
			tituloCadastro.setText("Cadastro de Despesa");
		else
			tituloCadastro.setText("Cadastro de Receita");
			
		
		Button btnCadastrar = (Button)findViewById(R.id.btnCadastrar);
		btnCadastrar.setOnClickListener(this.btnCadastrarClickListener);
	}

}
